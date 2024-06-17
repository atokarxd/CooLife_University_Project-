/*export function handleForm(event) { event.preventDefault(); }

export function time(id) {
    setInterval(()=>{
        this.date = id;
        var dateTime = new Date();
        var hrs= dateTime.getHours();
        var min= dateTime.getMinutes();
        if(min > 9){
            this.date.textContent = hrs + ":" + min;
        } else {
            this.date.textContent = hrs + ":0" + min;
        }
    })
}*/

export function setCookie(name, value, daysToLive) {
        const date = new Date();
        date.setTime(date.getTime() + (daysToLive * 24 * 60 * 60 * 1000));
        let expires = "expires=" + date.toUTCString();
        document.cookie = `${name}=${value}; ${expires}; path=/`;
};

export function deleteCookie(name) {
        setCookie(name, null, null)
};

export function getCookie(name) {
        const cDecoded = decodeURIComponent(document.cookie);
        const cArray = cDecoded.split("; ");
        let result = null;
        
        cArray.forEach(element => {
            if(element.indexOf(name) == 0) {
                result = element.substring(name.length + 1)
            }
        })
        return result;
};

export function darkmode_loading(checkbox, root) {
    //var checkbox = document.getElementById("darkmode");
    //var root = document.querySelector(":root");
    console.log(getCookie("darkmode"))

    if(getCookie("darkmode") == "true") {
        root.style.setProperty("--bg-clr-mode", "var(--bg-clr-dark)");
        root.style.setProperty("--clr-mode", "var(--clr-white)");
        root.style.setProperty("--navbar-mode", "var(--navbar-dark)");
        checkbox.checked = true;
    } else {
        root.style.setProperty("--bg-clr-mode", "var(--bg-clr-light)");
        root.style.setProperty("--clr-mode", "var(--clr-black)");
        root.style.setProperty("--navbar-mode", "var(--navbar-light)");
        checkbox.checked = false;
    }
}

export function sleep(ms) {
    return new Promise(resolve => setTimeout(resolve, ms));
}