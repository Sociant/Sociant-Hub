function mergeObjects(obj1, obj2) {
    var obj3 = {};
    for (var attrname in obj1) {
        obj3[attrname] = obj1[attrname];
    }
    for (var attrname in obj2) {
        obj3[attrname] = obj2[attrname];
    }
    return obj3;
}

function request(options) {
    var defaultOptions = {
        method: "GET",
        json: false,
        data: {},
        contentType: "application/x-www-form-urlencoded",
        charset: "UTF-8",
        data: null,
        overrideContentType: true,
    };

    if (typeof options !== "object") options = {};

    options = mergeObjects(defaultOptions, options);

    if (typeof options.url !== "string") throw "URL is required";

    if (options.overrideContentType && options.method == "POST" && options.data instanceof FormData) options.contentType = "multipart/form-data";
    else if (!options.overrideContentType && options.method == "POST" && options.data instanceof FormData && options.contentType == "application/x-www-form-urlencoded") {
        var queryString = "";
        for (var pair of options.data.entries()) if (typeof pair[1] == "string") queryString += (queryString ? "&" : "") + encodeURIComponent(pair[0]) + "=" + encodeURIComponent(pair[1]);

        options.data = queryString;
    }

    var request = new XMLHttpRequest();
    request.open(options.method, options.url, true);

    request.setRequestHeader("X-Requested-With", "XMLHttpRequest");
    if (options.contentType != "multipart/form-data") request.setRequestHeader("Content-Type", options.contentType + "; charset=" + options.charset);

    request.onload = function () {
        if (this.status >= 200 && this.status < 400) {
            if (typeof options.success == "function") options.success(options.json ? JSON.parse(this.response) : this.response, this);
        } else if (typeof options.error == "function") options.error(this.response, this);
    };

    request.onerror = function () {
        if (typeof options.error == "function") options.error("", this);
    };

    if (options.method == "POST" && options.data != null) {
        request.send(options.data);
    } else request.send();
}

function onEvent(element, eventName, elementSelector, handler, exact) {
    element.addEventListener(
        eventName,
        function (e) {
            for (var target = e.target; target && target != this; target = target.parentNode)
                if (target.matches(elementSelector)) {
                    handler.call(target, e);
                    break;
                }
        },
        false
    );
}

function setCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime() + (days*24*60*60*1000));
        expires = "; expires=" + date.toUTCString();
    }
    document.cookie = name + "=" + (value || "")  + expires + "; path=/";
}
function getCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return null;
}

function getLocalStorageItem(key, defaultValue) {
    var item = localStorage.getItem(key);
    if(item != null) return item;
    if(typeof defaultValue !== "undefined") return defaultValue;
    return null;
}

function hasAttribute(element, attribute) {
    return element.getAttribute(attribute) != null;
}

document.querySelector("#footer .toggle span").addEventListener("click",function() {
    var body = document.querySelector("body").classList;
    var mode = "light";

    if(body.contains("dark")) {
        body.remove("dark");
        body.add("sepia");
        mode = "sepia";
    } else if(body.contains("sepia")) {
        body.remove("sepia");
        mode = "light";
    } else {
        body.add("dark");
        mode = "dark";
    }

    setCookie("darkmode",mode);
});

if(getCookie("darkmode") == null && window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches) {
    var body = document.querySelector("body").classList;
    body.add("dark");

    setCookie("darkmode","dark");
}

document.querySelector("#navigation .profile").addEventListener("click",function() {
    if(this.classList.contains("open"))
        this.classList.remove("open");
    else
        this.classList.add("open");
});

document.querySelector("#navigation .profile .menu").addEventListener("click",function(e) {
    e.stopPropagation();
});