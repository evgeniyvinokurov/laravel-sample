
document.addEventListener("DOMContentLoaded", function(){
    if (!document.querySelector(".orders"))
        return false;

    let ordersEl = document.querySelector(".orders-view");
    let oneOrderEl = document.querySelector(".one-order-view");
    let orderStatusSelectEl = document.querySelector(".one-order-view .status select");

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

    orderStatusSelectEl.addEventListener("change", function(e){
        let status = this.value;
        let idforeupdate = this.getAttribute("data-id");

        let fdata = new FormData();
        fdata.append("id", idforeupdate);
        fdata.append("status", status);

        doAjaxPost("/order/update", fdata, function(data){
            if (data.status == "ok") {
                init();
            }
        })            
    });

    let initOrderEvents = function(){
        let ordersEls = document.querySelectorAll(".orders-view .order");
        for (let o of ordersEls) {
            o.addEventListener("click", function(e){ 
                let order = JSON.parse(unescape(this.attributes["data-src"].value));

                document.querySelector(".one-order-view .id").innerHTML = order["id"];
                document.querySelector(".one-order-view .name").innerHTML = order["name"];
                document.querySelector(".one-order-view .created_at").innerHTML = order["created_at"];
                document.querySelector(".one-order-view .product_name").innerHTML = order["product_name"];
                document.querySelector(".one-order-view .product_price").innerHTML = order["product_price"];
                document.querySelector(".one-order-view .comment").innerHTML = order["comment"];
                document.querySelector(".one-order-view .status select").value = order["status"];

                document.querySelector(".one-order-view .status select").setAttribute("data-id", order.id);

                oneOrderEl.classList.remove("hide");
            });
        }
        let ordersElsRemove = document.querySelectorAll(".orders-view .order .order-remove");
        for (let o of ordersElsRemove) {
            o.addEventListener("click", function(e){ 
                e.stopPropagation();console.log(this)
                let order = JSON.parse(unescape(this.parentElement.attributes["data-src"].value));
                console.log(order)
                oneOrderEl.classList.add("hide");

                let fdata = new FormData();
                fdata.append("id", order.id);
                doAjaxPost("/order/delete", fdata, function(data){
                    if (data.status == "ok") {
                        let el = document.querySelector("#o" + order.id);
                        el.remove();
                    }
                })            
            });
        }
    }

    let makeAllOrders = function(objs){
        let ordersHtml = "<table>";
        for (let order of objs) {
            ordersHtml += "<tr id='o" + order.id + "' class='order' data-src='" + escape(JSON.stringify(order)) + "'>";
            ordersHtml += "<td>" + order["id"] + "</td>";
            ordersHtml += "<td>" + order["created_at"] + "</td>";
            ordersHtml += "<td>" + order["name"] + "</td>";
            ordersHtml += "<td>" + order["status"] + "</td>";
            ordersHtml += "<td>" + order["comment"] + "</td>";
            ordersHtml += "<td>" + order["product_price"] + "</td>";
            ordersHtml += "<td class='order-remove'>✕</td>";
            ordersHtml += "</tr>";
        }
        ordersHtml += "</table>";
        ordersEl.innerHTML = ordersHtml;
    }

    let init = function(){

        let fdata = new FormData();

        doAjaxPost("/order/all", fdata, function(data){
            if (data.status == "ok") {
                makeAllOrders(data.orders);                        
                initOrderEvents();
            }
        })                  
    }

    init();
});