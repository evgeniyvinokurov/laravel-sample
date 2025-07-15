<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\User;
use App\Models\Category;
use App\Models\Gender;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Validator;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;


class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {            
        $user = Auth::user();
        if (empty($user)){
            $user = False;
        }
        $categories = Category::all();
        return view('products', ["categories" => $categories, "user" => $user] );
    }

    public function all(FormRequest $request) {
        $user = Auth::user();
        $pids = [];

        if (!empty($user)) {
            $carts = Cart::where('user', $user->id)->get();
            $pcarts = [];

            foreach($carts as $c) {
                $pids[] = $c->product;
                $pcarts[$c->product] = $c->quantity;
            }                   
            $productsIn = Product::whereIn('id', $pids)->get(); 
            $productsCart = [];

            foreach($productsIn as $pc) {
                $item = $pc;
                $item["quantity"] = $pcarts[$item->id];
                $productsCart[] = $pc;
            }       
            $isAuth = TRUE;
        } else {
            $productsCart = [];
            $isAuth = FALSE;
        }

        $products = Product::all();        
        return ["status" => "ok", "products" => $products, "cart"=> $productsCart, "auth"=> $isAuth];
    }

    /**$user
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(FormRequest $request)
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
                "category" => $request->category,
                "quantity" => 10
            ];
            $product = Product::create($product);
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
    public function update(FormRequest $request, Product $product)
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
    public function destroy(FormRequest $request, Product $product)
    {        
        $id = $request->id;
        $p = Product::where('id', $id);        
        $p->delete();        
        return ["status" => "ok", "id" => $id];
    }
}
