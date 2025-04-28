<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\CartController;
use App\Models\Product;
use App\Models\Order;
use App\Models\Category;
use App\Models\User;

 
Route::get('/greeting', function () {
    return 'Hello World';
});

Route::get('/', [ProductController::class, 'index']);

Route::get('/order/all', function () {
    return view('orders');
});

Route::post('/cart/add', [CartController::class, 'create']);

Route::post('/order/all', function () {
    $orders = Order::all();    
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
        $item["product_price"] = $productkeys[$o["product"]]["price"];

        $ordersWithNames[] = $item;
    }

    return ["status" => "ok", "orders" => $ordersWithNames];
});

Route::post('/product/all', [ProductController::class, 'all']);

Route::post('/product/create', [ProductController::class, 'store']);
Route::post('/product/delete', [ProductController::class, 'destroy']);
Route::post('/product/update', [ProductController::class, 'update']);

Route::post('/order/create', [OrderController::class, 'store']);
Route::post('/order/delete', [OrderController::class, 'destroy']);
Route::post('/order/update', [OrderController::class, 'update']);