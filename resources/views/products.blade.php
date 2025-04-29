<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Laravel</title>
        @vite('resources/js/app.js')    
        @vite('resources/css/app.css')
    </head>    
    <body class="products m-20">
        <h1><span class="font-semibold">Products</span>&nbsp<a class="m-10" href="/order/all">Orders</a></h1>

        <div class="product-selected grid w-60 m-10 border-2 border-solid p-2">
            <input type="text" placeHolder="name" class="name-text"></input>
            <input type="hidden"  class="id"></input>
            <input type="text" placeHolder="description"  class="description-text"></input>
            <select name="category" class="category-select">
                @foreach ($categories as $cat)  
                    <option value="{{ $cat->id }}">
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            <input type="text" placeHolder="price 2 decimal"  class="price-text"></input>
            <div class="inline-block">
                <input type="submit" value="create" class="create-btn border-2 border-solid cursor-pointer p-1"></input>
                <input type="submit" value="update" class="update-btn border-2 border-solid cursor-pointer p-1 hide"></input>
                <input type="submit" value="cancel" class="cancel-btn border-2 border-solid cursor-pointer p-1 hide"></input>
                <input type="submit" value="delete" class="delete-btn border-2 border-solid cursor-pointer p-1 hide"></input>
            </div>
        </div>

        <div class="products-view m-2"></div>

        <label for="cart-view m-2" class="label-cart">Cart</label>
        <div id="cart-view m-2" class="cart-view"></div>

        <div class="message hide"></div>
        
        <div class="make-order-container mt-10">
            <h2 class="make-order-title hide">Make order</h2>

            <input type="text"  class="order-name-text hide" placeHolder="person name"></input>
            <input type="text"  class="comment-text hide" placeHolder="comment"></input>
            <input type="submit" value="order" class="order-create-btn hide cursor-pointer"></input>
        </div>
        <style>
            .product {
                display: inline-block;
                padding: 20px;
                background: lightgray;
                margin: 10px;
                cursor: pointer;
            }

            .hide {
                display: none;
            }

            .message {
                padding: 20px;
                margin: 10px;
            }
        </style>
    </body>
</html>