<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return Product::all();
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'product_name' => 'required',
            'product_cost' => 'required | integer',
            'product_selling' => 'required | integer',
            'product_stock' => 'required | integer',
        ]);

        $product = Product::create([
            'name' => $fields['product_name'],
            'cost' => $fields['product_cost'],
            'selling' => $fields['product_selling'],
            'stock' => $fields['product_stock'],
        ]);

        return response()->json(['product' => $product]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $fields = $request->validate([
            'product_name' => 'required',
            'product_cost' => 'required | integer',
            'product_selling' => 'required | integer',
            'product_stock' => 'required | integer',
        ]);

        $product = Product::where('id', $id)->update([
            'name' => $fields['product_name'],
            'cost' => $fields['product_cost'],
            'selling' => $fields['product_selling'],
            'stock' => $fields['product_stock'],
        ]);

        return response()->json(['product' => $product]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Product::find($id)->delete();
        return response()->json([
            'message' => 'Deleted'
        ], 201);
    }
}
