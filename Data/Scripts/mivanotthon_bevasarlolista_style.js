import { setCookie, getCookie, darkmode_loading, sleep } from './function.js';

document.addEventListener('DOMContentLoaded', function() {
    var checkbox = document.getElementById("darkmode");
    var root = document.querySelector(":root");
    console.log(getCookie("darkmode"))

    darkmode_loading(checkbox, root);
    /*if(getCookie("darkmode") == "true") {
        root.style.setProperty("--bg-clr-mode", "var(--bg-clr-dark)");
        root.style.setProperty("--clr-mode", "var(--clr-white)");
        root.style.setProperty("--navbar-mode", "var(--navbar-dark)");
        checkbox.checked = true;
    } else {
        root.style.setProperty("--bg-clr-mode", "var(--bg-clr-light)");
        root.style.setProperty("--clr-mode", "var(--clr-black)");
        root.style.setProperty("--navbar-mode", "var(--navbar-light)");
        checkbox.checked = false;
    }*/

   var loader = document.getElementById("loader");

    window.addEventListener("load", function(){
        sleep(400).then(() => { loader.style.display = "none"; });
    })
    
    const profile_icon = document.getElementById("littleProfile");
    const profile_menu = document.querySelector(".profile");
    const exit_icon = document.getElementById("exit");

    profile_icon.addEventListener('click', function() {
        profile_menu.setAttribute('class','profile');
        profile_menu.removeAttribute('id', 'profile-remove')
    });

    exit_icon.addEventListener('click', function () {
        profile_menu.setAttribute("id", 'profile-remove')
    });


    checkbox.addEventListener("click", function(){
        if(checkbox.checked == true) {
            root.style.setProperty("--bg-clr-mode", "var(--bg-clr-dark)");
            root.style.setProperty("--clr-mode", "var(--clr-white)");
            root.style.setProperty("--navbar-mode", "var(--navbar-dark)");
            setCookie("darkmode", true, 365);
        } else {
            root.style.setProperty("--bg-clr-mode", "var(--bg-clr-light)");
            root.style.setProperty("--clr-mode", "var(--clr-black)");
            root.style.setProperty("--navbar-mode", "var(--navbar-light)");
            setCookie("darkmode", false, 365);
        }
    })

    var checkbox_notification = document.getElementById("notification");

    checkbox_notification.addEventListener("click", function(){
        if(checkbox_notification.checked == true) {
            setCookie("notification", true, 365);
        } else {
            setCookie("notification", false, 365);
        }
    })

    setInterval(()=>{
        var date = document.getElementById("date");
        var dateTime = new Date();
        var hrs= dateTime.getHours();
        var min= dateTime.getMinutes();
        if(min > 9){
            date.textContent = hrs + ":" + min;
        } else {
            date.textContent = hrs + ":0" + min;
        }
    })
})