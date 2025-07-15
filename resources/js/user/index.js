document.addEventListener("DOMContentLoaded", function(){

    if (!document.querySelector(".profile"))
        return false;

    let emailEl = document.querySelector("[name='email']");
    let passwordEl = document.querySelector("[name=password]");
    let submitEl = document.querySelector("[type='submit']");
    let logoutEl = document.querySelector(".logout");
    let errorEl = document.querySelector(".error");

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

    let init = function(){
        submitEl.addEventListener("click", function(e){
            e.preventDefault();
            let fdata = new FormData();
            fdata.append("email", emailEl.value);
            fdata.append("password", passwordEl.value);

            doAjaxPost("/login", fdata, function(data){

                if (data.status == "ok") {
                    errorEl.innerHTML = "ok";
                    setTimeout(() => {
                        errorEl.innerHTML = "";
                        document.querySelector(".userinfo").innerHTML = data.userinfo;
                    }, 2000);
                } else {
                    errorEl.innerHTML = "Ошибка";

                    setTimeout(() => {
                        errorEl.innerHTML = "";
                    }, 2000);
                }
            })
        })                
        
        logoutEl.addEventListener("click", function(e){
            e.preventDefault();
            let fdata = new FormData();
            doAjaxPost("/user/logout", fdata, function(data){
                if (data.status == "ok") {
                    errorEl.innerHTML = "ok";
                    setTimeout(() => {
                        errorEl.innerHTML = "";
                        document.querySelector(".userinfo").innerHTML = "guest, please login!";
                    }, 2000);
                } else {
                    errorEl.innerHTML = "Ошибка";
                    setTimeout(() => {
                        errorEl.innerHTML = "";
                    }, 2000);
                }
            })
        })                       
    }

    console.log("test0");
    init();
});