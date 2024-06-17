import { sleep } from './function.js';

document.addEventListener('DOMContentLoaded', function() {
   var loader = document.getElementById("loader");

    window.addEventListener("load", function(){
        sleep(600).then(() => { loader.style.display = "none"; });
    })


    var links = document.querySelectorAll('a[href^="#"]');

    links.forEach(function(link) {
        link.addEventListener('click', function(event) {
            // Prevent the default action
            event.preventDefault();

            // Get the target element
            var targetId = this.getAttribute('href');
            var targetElement = document.querySelector(targetId);

            // Smooth scroll to target
            if (targetElement) {
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });

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
});