<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|decimal:2',
            'category' => 'required'
        ]);
 
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $er = ["status" => "error", "error" => $error];
            return $er;
        } else {
            $product = [
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "category" => $request->category
            ];
            Product::create($product);
            return ["status" => "ok", "product" => $product];
        } 
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'description' => 'required',
            'price' => 'required|decimal:2',
            'category' => 'required'
        ]);
 
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $er = ["status" => "error", "error" => $error];
            return $er;
        } else {
            $product = Product::where('id', $request->id)->get();

            $product->toQuery()->update([
                "name" => $request->name,
                "description" => $request->description,
                "price" => $request->price,
                "category" => $request->category
            ]);

            $product = Product::where('id', $request->id)->get();

            return ["status" => "ok", "product" => $product[0]];
        } 
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UpdateProductRequest $request, Product $product)
    {        
        $id = $request->id;
        $p = Product::where('id', $id);        
        $p->delete();        
        return ["status" => "ok", "id" => $id];
    }
}
