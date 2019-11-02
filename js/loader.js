loadResources();

function loadResources() {
    if (document.getElementById("main-content") !== null) { // bootstrap is used
        loadJS("js/vendors/jquery.min.js");

        loadJS("js/vendors/bootstrap.min.js");

        if (document.getElementsByTagName("form").length > 0) { // bootstrap dialog is probably used
            loadCSS("assets/bootstrap-dialog/bootstrap-dialog.min.css");
            loadJS("assets/bootstrap-dialog/bootstrap-dialog.min.js");
        }
    }
}

function loadCSS(href) {
    var css = document.createElement("link");
    css.href = href;
    css.rel = "stylesheet";
    css.property = "stylesheet";
    css.type = "text/css";

    document.body.appendChild(css);
}

function loadJS(src) {
    var js = document.createElement("script");
    js.src = src;

    document.body.appendChild(js);
}
