<?php

namespace App\Http\Controllers;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

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
        $products = Product::all();
        Log::debug('product type:' . gettype($products));
//        return view('welcome');
    return Inertia::render('Products', [
            'products' => $products
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

        return redirect()->route('products.index')->with('success','Product has been created successfully.');
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

        return redirect()->route('products.index')->with('success','Product has been updated successfully');
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
        return redirect()->route('products.index')->with('success','Product has been deleted successfully');
    }
}
