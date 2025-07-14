<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\UserController;
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

Route::get('/order/all', [OrderController::class, 'index'])->middleware('auth');
Route::post('/order/all', [OrderController::class, 'all'])->middleware('auth');

Route::post('/product/all', [ProductController::class, 'all']);
Route::post('/product/create', [ProductController::class, 'store'])->middleware('auth');
Route::post('/product/delete', [ProductController::class, 'destroy'])->middleware('auth');
Route::post('/product/update', [ProductController::class, 'update'])->middleware('auth');

Route::post('/order/create', [OrderController::class, 'store'])->middleware('auth');
Route::post('/order/delete', [OrderController::class, 'destroy'])->middleware('auth');
Route::post('/order/update', [OrderController::class, 'update'])->middleware('auth');


Route::post('/user/registration', [UserController::class, 'registration']);
Route::post('/login', [UserController::class, 'login']);
Route::get('/user/profile', [UserController::class, 'profile']);
Route::get('/user', [UserController::class, 'user'])->name('login');
Route::post('/user/logout', [UserController::class, 'logout']);