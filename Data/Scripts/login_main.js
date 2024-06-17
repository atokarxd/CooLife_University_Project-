import { sleep } from './function.js';

document.addEventListener('DOMContentLoaded', function() {
 var loader = document.getElementById("loader");

  window.addEventListener("load", function(){
      sleep(400).then(() => { loader.style.display = "none"; });
  })

  const sign_in_btn = document.querySelector("#sign-in-btn");
  const sign_up_btn = document.querySelector("#sign-up-btn");
  const container = document.querySelector(".container");

  sign_up_btn.addEventListener("click", () => {
    container.classList.add("sign-up-mode");
  });

  sign_in_btn.addEventListener("click", () => {
    container.classList.remove("sign-up-mode");
  });

  //var date = document.getElementById("date");
  //time(date);
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


