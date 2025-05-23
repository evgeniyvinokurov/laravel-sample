
document.addEventListener("DOMContentLoaded", function(){
    if (!document.querySelector(".products"))
        return false;

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
    let cartEl = document.querySelector(".cart-view");
    let cartLabelEl = document.querySelector(".label-cart");

    let lblProduct  = document.querySelector(".label-product");
    let selectedProduct  = document.querySelector(".product-selected");

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

        let csrftoken = document.querySelector('meta[name="csrf-token"]').getAttribute("content");
        data.append("_token", csrftoken)
        xhttp.send(data);
    }

    let makeProduct = function(obj){
        return "<div id='p" + obj.id + "' class='product new min-w-64' data-src='" + escape(JSON.stringify(obj)) + "'>" + obj["name"] + "<span class='add m-2 float-right'>add</span></div>";
    }

    let makeProductCart = function(obj){
        return "<div id='p" + obj.id + "' class='product-cart new' data-src='" + escape(JSON.stringify(obj)) + "'>" + obj["name"] + "<span class='border-2 border-solid cursor-pointer p-1 remove m-2'>remove</span></div>";
    }

    let makeAllProducts = function(objs){
        productsEl.innerHTML = "";

        for (let i of objs)
            productsEl.innerHTML = productsEl.innerHTML + makeProduct(i);
        
        initProductEvents();
    }

    let makeCart = function(objs){
        cartEl.innerHTML = "";

        for (let i of objs)
            cartEl.innerHTML = cartEl.innerHTML + makeProductCart(i);

        if (objs.length > 0) {
            showOrder();
            cartLabelEl.classList.remove("hide");
        } else {
            hideOrder();
            cartLabelEl.classList.add("hide");
        }

        initCartEvents();
    
    }

    let showOrder = function(){
        makeOrderTitleEl.classList.remove("hide");
        orderNameEl.classList.remove("hide");                        
        commentEl.classList.remove("hide");
        createOrderEl.classList.remove("hide");
    }

    let hideOrder = function() {
        makeOrderTitleEl.classList.add("hide");
        orderNameEl.classList.add("hide");                        
        commentEl.classList.add("hide");
        createOrderEl.classList.add("hide");        
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
                selectedProduct.classList.remove("hide");

                createEl.classList.add("hide");
            });
        }

        let productsAddEls = document.querySelectorAll(".products-view .product .add");
        for (let p of productsAddEls) {
            p.addEventListener("click", function(e){ 
                e.preventDefault();
                e.stopPropagation();

                let product = JSON.parse(unescape(this.parentElement.attributes["data-src"].value));

                let fdata = new FormData();                
                fdata.append("product", product["id"]);

                doAjaxPost("/cart/add", fdata, function(data){
                    if (data.status == "ok") {
                        showCart();
                    }
                })   

            });
        }
    }

    let initCartEvents = function(){
        let productsRemoveEls = document.querySelectorAll(".cart-view .product-cart .remove");
        for (let p of productsRemoveEls) {
            p.addEventListener("click", function(e){ 
                e.preventDefault();
                e.stopPropagation();

                let product = JSON.parse(unescape(this.parentElement.attributes["data-src"].value));

                let fdata = new FormData();                
                fdata.append("product", product["id"]);

                doAjaxPost("/cart/remove", fdata, function(data){
                    if (data.status == "ok") {
                        makeCart(data.cart);
                    }
                })   

            });
        }
    }

    let showCart = function(){
        let fdata = new FormData();

        doAjaxPost("/cart/", fdata, function(data){
            if (data.status = "ok"){
                makeCart(data.cart);
            }
        })
    }

    let hideCart = function(){
        makeCart([]);          
    }

    let init = function(){
        let fdata = new FormData();

        doAjaxPost("/product/all", fdata, function(data){
            if (data.status == "ok") {
                makeAllProducts(data.products);    
                makeCart(data.cart);                    
            }
        })   
    }

    lblProduct.addEventListener("click", function(e){  
        selectedProduct.classList.toggle("hide");
    })

    cancelEl.addEventListener("click", function(e){ 
        updateEl.classList.add("hide");
        cancelEl.classList.add("hide");
        deleteEl.classList.add("hide");
        createOrderEl.classList.add("hide");
        commentEl.classList.add("hide");
        orderNameEl.classList.add("hide");
        makeOrderTitleEl.classList.add("hide");
        createEl.classList.remove("hide");
        selectedProduct.classList.add("hide");

        priceEl.value = "";
        nameEl.value = "";
        descriptionEl.value = "";
        categoryEl.value = "";
        idEl.value = "";
    })

    createEl.addEventListener("click", function(e){
        let fdata = new FormData();
        fdata.append("price", priceEl.value);
        fdata.append("description", descriptionEl.value);
        fdata.append("name", nameEl.value);
        fdata.append("category", categoryEl.value);

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

        let fdata = new FormData();
        fdata.append("order_name", orderNameEl.value);
        fdata.append("comment", commentEl.value);

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

                    hideCart();
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

        let fdata = new FormData();
        fdata.append("id", idforeupdate);
        fdata.append("price", priceEl.value);
        fdata.append("description", descriptionEl.value);
        fdata.append("name", nameEl.value);
        fdata.append("category", categoryEl.value);             

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

        let fdata = new FormData();
        fdata.append("id", idforedelete);

        doAjaxPost("/product/delete", fdata, function(data){
            if (data.status == "ok") {
                let el = document.querySelector("#p" + idforedelete);
                el.remove();
            }
        })            
    });

    init();

});