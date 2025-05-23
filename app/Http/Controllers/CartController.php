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

        foreach($carts as $c) {
            $pids[] = $c->product;
        }       

        $productsCart = Product::whereIn('id', $pids)->get();        

        return ["status" => "ok", "cart"=> $productsCart];
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(FormRequest $request)
    {        
        $user = Auth::user();

        $cart = new Cart();        
        
        $cart->user = $user->id;
        $cart->product = $request->product;

        $cart->save();
        return ["status" => "ok"];

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

        if ($cartDelete)
            $cartDelete->delete();

        $pids = [];

        $carts = Cart::where('user', $user->id)->get();

        foreach($carts as $c) {
            $pids[] = $c->product;
        }       

        $productsCart = Product::whereIn('id', $pids)->get();        

        return ["status" => "ok", "cart"=> $productsCart];    
    }
}
