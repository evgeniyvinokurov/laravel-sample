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

 
Route::get('/', [ProductController::class, 'index']);

Route::post('/cart/add', [CartController::class, 'create']);
Route::post('/cart/remove', [CartController::class, 'destroy']);
Route::post('/cart', [CartController::class, 'index']);

Route::get('/order/all', [OrderController::class, 'index']);
Route::post('/order/all', [OrderController::class, 'all']);

Route::post('/product/all', [ProductController::class, 'all']);
Route::post('/product/create', [ProductController::class, 'store']);
Route::post('/product/delete', [ProductController::class, 'destroy']);
Route::post('/product/update', [ProductController::class, 'update']);

Route::post('/order/create', [OrderController::class, 'store']);
Route::post('/order/delete', [OrderController::class, 'destroy']);
Route::post('/order/update', [OrderController::class, 'update']);