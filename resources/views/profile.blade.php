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
    <body class="profile m-20">
        @include('menu')

        <h3>login</h3>
        <form>
            <input name="email" type="email"></input>
            <input name="password" type="password"></input>
            <input type="submit" value="send"></input>
            <span class="error"></span>
        </form>
        <a class="logout" href="/user/logout">logout</a>
        
        <style>
            input {
                border: 2px solid grey;
                padding: 2px;
            }

            .logout, .error {
                display: block;
                padding: 10px;
            }

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