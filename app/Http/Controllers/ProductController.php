<?php

namespace App\Http\Controllers;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use App\Models\Product;
use App\Models\Techno;
use App\Models\Status;
use App\Models\Event;

class ProductController extends Controller
{
    public $defaultPath= '/uploads/default.png';

    protected function enableAdmin()
    {
        return Auth::user()->can("edit product") || Auth::user()->can("edit user");
    }

   /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $user= Auth::user();

        if ($user->can('reading')) {
            $products = Product::where('title','<>','none')->get();
            return Inertia::render('Products', [
                    'products'    => $products,
                    'enableAdmin' => $this->enableAdmin(),
                ]);
        } else {
            return redirect()->route('profile.edit');
        }
    }

    public function allProducts()
    {
        return response()->json([
            'products'    => Product::where('title','<>','none')->get(),
        ]);
    }

    public function getProduct($id)
    {
        $product = Product::find($id);

        $events   = Event::where([['product_id', $product->id],['active', true]])->get();
        $sns= array();
        foreach ($events as $ev) { 
            $sns[]= $ev->sn_n;
        }
        $table= [];
        foreach (array_unique($sns, SORT_NUMERIC) as $n) {
            $events_sn= Event::where([['product_id',$product->id],['sn_n',$n],['active', true]])->orderBy('date', 'asc');
            $table+= [$n => $events_sn->get()->toArray()];
        }

        return Inertia::render('Product', [
            'product'     => $product,
            'tables'      => $table,
            'technos'     => Techno::withTrashed()->where('product_id', $product->id)->get(),
            'status'      => Status::withTrashed()->get(),
            'enableEdit'  => auth()->user()->can('edit event'),
            'enableAdmin' => $this->enableAdmin(),
        ]);

    }
}
