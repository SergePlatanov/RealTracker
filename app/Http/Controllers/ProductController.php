<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

use App\Models\Product;
use App\Models\Techno;
use App\Models\Status;
use App\Models\Event;
use App\Models\Number;

class ProductController extends Controller
{
   /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        Log::debug('ProductController::index');
        $products = Product::where('title','<>','none')->get();
        Log::debug('product type:' . gettype($products));
//        return view('welcome');
        return Inertia::render('Products', [
            'products' => $products
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
            'product'  => $product,
            'tables'   => $table,
            'technos'  => Techno::where('product_id', $product->id)->get(),
            'status'   => Status::all(),
            'numbers'  => Number::where('product_id', $product->id)->get(),
        ]);

    }

    /**
    * Show the form for creating a new resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function create()
    {
        return Inertia::render('Product/Create');
    }

    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        Log::debug('ProductController::store');

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'path' => 'required',
        ]);
        
        Product::create($request->post());

        return redirect()->route('service')->with('success','Product has been created successfully.');
    }

    /**
    * Show the form for editing the specified resource.
    *
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
    public function edit($id)
    {
        $product= Product::find($id);
        Log::debug('ProductController::edit - ' . $product->id);
        return Inertia::render('Product/Edit', [
            'product' => $product
        ]);
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request, $id)
    {
        Log::debug('ProductController::update - ' . $id);

        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'path' => 'required',
        ]);
        
        Product::find($id)->fill($request->post())->save();

        return redirect()->route('service')->with('success','Product has been updated successfully');
    }

    /**
    * Remove the specified resource from storage.
    *
    * @param  \App\Product  $product
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        Product::find($id)->delete();
        return redirect()->route('service')->with('success','Product has been deleted successfully');
    }
}
