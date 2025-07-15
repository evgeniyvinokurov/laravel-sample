<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Http\FormRequest;

use App\Models\Order;
use App\Models\Cart;
use App\Models\Product;
use App\Models\User;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('orders');              
    }

    public function all(){
        $user = Auth::user();
        
        if ($user->admin === "Y"){
            $orders = Order::all();  
        } else {
            $orders = Order::where(["link" => $user->id])->get();  
        }  
        $ids = [];

        
        foreach($orders as $o) {
            $ids[] = $o["product"];    
        }

        $products = Product::whereIn('id', $ids)->get();
        $productkeys = [];
        foreach($products as $p){
            $productkeys[$p->id] = $p;
        }

        $ordersWithNames = [];

        foreach($orders as $o){
            $item = $o;

            $item["product_name"] = $productkeys[$o["product"]]["name"];
            $item["product_price"] = $o["price"];
            $item["product_total"] = floatval($o["price"]) * floatval($o["quantity"]);

            $ordersWithNames[] = $item;
        }

        return ["status" => "ok", "orders" => $ordersWithNames, "admin" => $user->admin];
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
    public function store(FormRequest $request)
    {
        $validator = Validator::make($request->all(), [
            'comment' => 'required|max:255',
            'order_name' => 'required'
        ]);
 
        if ($validator->fails()) {
            $error = $validator->errors()->first();
            $er = ["status" => "error", "error" => $error];
            return $er;
        } else {
            $user = Auth::user();
            $pids = [];
    
            $carts = Cart::where('user', $user->id)->get();        
            $number = rand(9000000, 9999999);

            foreach($carts as $c) {
                $order = [
                    "number" => $number,
                    "name" => $request->order_name,
                    "product" => $c->product,
                    "comment" => $request->comment,
                    "quantity" => $c->quantity,
                    "link" => $c->user,
                    "price" => $c->price
                ];
                Order::create($order);         
                
                $cartDelete = Cart::where([['product', $c->product], ['user', $user->id]]);

                if ($cartDelete)
                    $cartDelete->delete();
            }
            
            return ["status" => "ok"];
        } 
        
    }

    /**
     * Display the specified resource.
     */
    public function show(Order $order)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Order $order)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(FormRequest $request, Order $order)
    {        

        $loggedInUser = Auth::user();

        if ($loggedInUser->admin === "Y") {
            $order = Order::where('id', $request->id)->first();
            $user = User::where(["id" => $order->link])->first();

            $tobeBonuses = $user->bonuses - (floatval($order->quantity) * floatval($order->price));

            if ($tobeBonuses > 0 && $request->status == "выполнен") {
                $order = Order::where(['id' => $request->id])->update([
                    "status" => $request->status            
                ]);
                
                User::where(["id" => $user->id])->update(["bonuses" => $tobeBonuses]);
                $o = Order::where('id', $request->id)->first();        
                return ["status" => "ok", "product" => $o];

            } else {
                return ["status" => "error"];
            }
        } else {
            return ["status" => "error"];
        }

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(FormRequest $request, Order $o)
    {            
        $id = $request->id;
        $o = Order::where('id', $id);        
        $o->delete();        
        return ["status" => "ok", "id" => $id];
    }
}
