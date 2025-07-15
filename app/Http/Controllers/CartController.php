<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use App\Http\Requests\IndexRequest;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Cart;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

use Symfony\Component\HttpFoundation\Session\Session;


class CartController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {        
        $user = Auth::user();
        $pids = [];

        $carts = Cart::where('user', $user->id)->get();

        $cartsForDisplay = [];
        $cartByProduct = [];

        foreach($carts as $c) {
            $pids[] = $c->product;            
            $cartByProduct[$c->product] = $c;
        }       

        $productsCart = Product::whereIn('id', $pids)->get();        
        $products = [];

        foreach($productsCart as $p) {            
            $item = $p;
            $item["quantity"] = $cartByProduct[$p->id]->quantity;
            $cartsForDisplay[] = $item;
        }       

        return ["status" => "ok", "cart"=> $cartsForDisplay];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(FormRequest $request)
    {        
        $user = Auth::user();     
        $cart = Cart::where(['product' => $request->product, 'user' => $user->id])->first();
        $p = Product::where(['id' => intval($request->product)])->first();

        $quantity = $p->quantity;
        
        if ($cart !== null){            
            if (($quantity + $cart->quantity) > $cart->quantity + 1) {
                $newQuantity = $quantity - 1;
                $addedQuantity = $cart->quantity + 1;
            } else {
                $addedQuantity = $quantity + $cart->quantity;
                $newQuantity = 0;
            }     

            Cart::where(["id" => $cart->id])->update(["quantity" => $addedQuantity]);
            Product::where(["id" => $cart->product])->update(["quantity" => $newQuantity]);
        } else {            
            $cart = new Cart();

            $cart->user = $user->id;
            $cart->product = $request->product;
            $cart->quantity = 1;
            $cart->price = $p->price;

            $newQuantity = $quantity - 1;
            $addedQuantity = 1;            

            Product::where(["id" => $cart->product])->update(["quantity" => $newQuantity]);

            $cart->save();
        }

        $quantityObj = ["pval" => $newQuantity, "cval" => $addedQuantity];

        return ["status" => "ok", "quantity" => $quantityObj];

    }

    /**
     * Quantity
     */
    public function quantity(FormRequest $request)
    {        
        $user = Auth::user();     

        $p = Product::where(['id' => intval($request->product)])->first();
        $quantity = $p->quantity;

        $cart = Cart::where(['product' => intval($request->product), 'user' => $user->id])->first();
        
        // var_dump($request->product);
        // var_dump($user->id);
        // var_dump($cart);

        if ($cart !== null){       
            if ($request->quantity <= ($quantity + $cart->quantity)) {
                $newQuantity = ($quantity + $cart->quantity) - $request->quantity;
                $addedQuantity = $request->quantity;
            } else {
                $addedQuantity = $quantity + $cart->quantity;
                $newQuantity = 0;
            }     

            Cart::where(["id" => $cart->id])->update(["quantity" => $addedQuantity]);
            Product::where(["id" => $cart->product])->update(["quantity" => $newQuantity]);
        }       

        $quantityObj = ["pval" => $newQuantity, "cval" => $addedQuantity];

        return ["status" => "ok", "quantity" => $quantityObj];

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormRequest $request)
    {        
        $user = Auth::user();
        
        $cartDelete = Cart::where([['product', $request->product], ['user', $user->id]]);

        if ($cartDelete) {
            $c = $cartDelete->first();            

            $p = Product::where(["id" => $c->product]);
            $quantity = $p->first()->quantity;
            $newQuantity = $c->quantity + $quantity;

            $p->update(["quantity" => $newQuantity]);
            $cartDelete->delete();
        }

        $pids = [];

        $carts = Cart::where('user', $user->id)->get();

        foreach($carts as $c) {
            $pids[] = $c->product;
        }       

        $productsCart = Product::whereIn('id', $pids)->get();   
        $quantityObj = ["pval" => $newQuantity, "cval" => 0];

        return ["status" => "ok", "cart"=> $productsCart, "quantity" => $quantityObj];    
    }
}
