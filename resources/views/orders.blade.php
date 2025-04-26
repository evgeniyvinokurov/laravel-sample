<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <title>Laravel</title>
        @vite('resources/js/app.js')

    </head>    
    <body class="orders">
        <h1><a href="/product/all">Products</a>&nbsp;Orders</h1>
        <input type="hidden"  class="id"></input>
        <div class="orders-view"></div>
        <div class="one-order-view hide">
            <div class="id"></div>
            <div class="created_at"></div>
            <div class="name"></div>
            <div class="status"><select data-id=""><option value="выполнен">выполнен</option><option value='новый'>новый</option></select></div>
            <div class="comment"></div>
            <div class="product_name"></div>
            <div class="product_price"></div>
        </div>
        <style>
            .hide {
                display: none;
            }

            .order-remove {
                cursor: pointer;
            }

            .order:hover {
                background: lightgrey;
            }

            td {
                padding: 10px;
            }
        </style>
    </body>
</html>