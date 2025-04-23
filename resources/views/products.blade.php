<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

    </head>    
    <body class="">
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
        <script type="text/javascript">
            let createEl = document.querySelector(".create-btn");
            let updateEl = document.querySelector(".update-btn");
            let cancelEl = document.querySelector(".cancel-btn");
            let deleteEl = document.querySelector(".delete-btn");
            let createOrderEl = document.querySelector(".order-create-btn");
            let messageEl = document.querySelector(".message");
            let makeOrderTitleEl = document.querySelector(".make-order-title");

            let orderNameEl = document.querySelector(".order-name-text");
            let commentEl = document.querySelector(".comment-text");

            let priceEl = document.querySelector(".price-text");
            let nameEl = document.querySelector(".name-text");
            let descriptionEl = document.querySelector(".description-text");
            let categoryEl = document.querySelector(".category-select");
            let idEl = document.querySelector(".id");

            let productsEl = document.querySelector(".products-view");

            let doAjaxPost = function(url, data, cb){
                var xhttp = new XMLHttpRequest();
                xhttp.onreadystatechange = function() {
                    if (this.readyState == 4 && this.status == 200) {
                        console.log("RESPONSE:", this.response)
                        let data = JSON.parse(this.response);        
                        cb(data);
                    }
                }
                xhttp.open("POST", url, true);
                xhttp.send(data);
            }

            let makeProduct = function(obj){
                return "<div id='p" + obj.id + "' class='product new' data-src='" + escape(JSON.stringify(obj)) + "'>" + obj["name"] + "</div>";
            }

            let makeAllProducts = function(objs){
                productsEl.innerHTML = "";

                for (let i of objs)
                    productsEl.innerHTML = productsEl.innerHTML + makeProduct(i);
                
                initProductEvents();
            }

            let initProductEvents = function(){
                let productsEls = document.querySelectorAll(".products-view .product");
                for (let p of productsEls) {
                    p.addEventListener("click", function(e){ 
                        let product = JSON.parse(unescape(this.attributes["data-src"].value));

                        priceEl.value = product["price"];
                        descriptionEl.value = product["description"];
                        nameEl.value = product["name"];
                        categoryEl.value = product["category"];   
                        idEl.value = product["id"];                              

                        updateEl.classList.remove("hide");
                        cancelEl.classList.remove("hide");
                        deleteEl.classList.remove("hide");

                        makeOrderTitleEl.classList.remove("hide");

                        orderNameEl.classList.remove("hide");                        
                        commentEl.classList.remove("hide");
                        createOrderEl.classList.remove("hide");

                        createEl.classList.add("hide");
                    });
                }
            }

            let init = function(){

                fdata = new FormData();
                fdata.append('_token', '<?php echo csrf_token(); ?>');

                doAjaxPost("/product/all", fdata, function(data){
                    if (data.status == "ok") {
                        makeAllProducts(data.products);                        
                    }
                })   
            }

            cancelEl.addEventListener("click", function(e){ 
                updateEl.classList.add("hide");
                cancelEl.classList.add("hide");
                deleteEl.classList.add("hide");
                createOrderEl.classList.add("hide");
                commentEl.classList.add("hide");
                orderNameEl.classList.add("hide");
                makeOrderTitleEl.classList.add("hide");
                createEl.classList.remove("hide");

                priceEl.value = "";
                nameEl.value = "";
                descriptionEl.value = "";
                categoryEl.value = "";
                idEl.value = "";
            })

            createEl.addEventListener("click", function(e){
                fdata = new FormData();
                fdata.append("price", priceEl.value);
                fdata.append("description", descriptionEl.value);
                fdata.append("name", nameEl.value);
                fdata.append("category", categoryEl.value);
                fdata.append('_token', '<?php echo csrf_token(); ?>');

                doAjaxPost("/product/create", fdata, function(data){
                    if (data.status == "ok") {
                        productsEl.innerHTML = productsEl.innerHTML + makeProduct(data.product);
                        priceEl.value = "";
                        descriptionEl.value = "";
                        nameEl.value = "";
                        initProductEvents();
                    } else {
                        messageEl.innerHTML = "Ошибка " + data.error;
                        messageEl.classList.remove("hide");

                        setTimeout(function(){
                            messageEl.innerHTML = "";
                            messageEl.classList.add("hide");
                        }, 2000)
                    }
                })            
            });

            createOrderEl.addEventListener("click", function(e){                
                let idfororder = idEl.value;

                fdata = new FormData();
                fdata.append("product_id", idfororder);
                fdata.append("order_name", orderNameEl.value);
                fdata.append("comment", commentEl.value);
                fdata.append('_token', '<?php echo csrf_token(); ?>');

                doAjaxPost("/order/create", fdata, function(data){
                    if (data.status == "ok") {
                        messageEl.innerHTML = "Заказ создан";
                        messageEl.classList.remove("hide");

                        setTimeout(function(){
                            messageEl.innerHTML = "";
                            messageEl.classList.add("hide");
                            createOrderEl.classList.add("hide");
                            commentEl.classList.add("hide");
                            orderNameEl.classList.add("hide");
                            makeOrderTitleEl.classList.add("hide");
                            commentEl.value = "";
                            orderNameEl.value = "";
                        }, 2000)
                    } else {
                        messageEl.innerHTML = "Ошибка";
                        messageEl.classList.remove("hide");

                        setTimeout(function(){
                            messageEl.innerHTML = "";
                            messageEl.classList.add("hide");
                        }, 2000)
                    }
                })            
            });

            updateEl.addEventListener("click", function(e){
                let idforeupdate = idEl.value;

                fdata = new FormData();
                fdata.append("id", idforeupdate);
                fdata.append("price", priceEl.value);
                fdata.append("description", descriptionEl.value);
                fdata.append("name", nameEl.value);
                fdata.append("category", categoryEl.value);
                fdata.append('_token', '<?php echo csrf_token(); ?>');                

                doAjaxPost("/product/update", fdata, function(data){
                    if (data.status == "ok") {
                        let el = document.querySelector("#p" + idforeupdate);
                        el.remove();

                        productsEl.innerHTML = productsEl.innerHTML + makeProduct(data.product);
                        initProductEvents();
                    } else {
                        messageEl.innerHTML = "Ошибка " + data.error;
                        messageEl.classList.remove("hide");

                        setTimeout(function(){
                            messageEl.innerHTML = "";
                            messageEl.classList.add("hide");
                        }, 2000)
                    }
                })            
            });
            
            deleteEl.addEventListener("click", function(e){
                let idforedelete = idEl.value;

                fdata = new FormData();
                fdata.append("id", idforedelete);
                fdata.append('_token', '<?php echo csrf_token(); ?>');

                doAjaxPost("/product/delete", fdata, function(data){
                    if (data.status == "ok") {
                        let el = document.querySelector("#p" + idforedelete);
                        el.remove();
                    }
                })            
            });

            init();
        </script>
    </body>
</html>