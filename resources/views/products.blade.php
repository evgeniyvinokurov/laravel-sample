<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Laravel</title>
        @vite('resources/js/app.js')
    </head>    
    <body class="products">
        <h1>Products&nbsp<a href="/order/all">Orders</a></h1>
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
        <input type="submit" value="create" class="create-btn"></input>
        <input type="submit" value="update" class="update-btn hide"></input>
        <input type="submit" value="cancel" class="cancel-btn hide"></input>
        <input type="submit" value="delete" class="delete-btn hide"></input>
        <div class="products-view"></div>
        <div class="message hide"></div>
        <h2 class="make-order-title hide">Make order</h2>
        <input type="text"  class="order-name-text hide" placeHolder="person name"></input>
        <input type="text"  class="comment-text hide" placeHolder="comment"></input>
        <input type="submit" value="order" class="order-create-btn hide"></input>
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