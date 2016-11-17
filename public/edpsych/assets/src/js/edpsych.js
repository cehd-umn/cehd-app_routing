"use strict";

var $ = require("jquery");
var cookies = require("js-cookie");

$(function() {
    var $open_button = $("#nav-expand");
    var $close_button = $("#navhide");
    var $nav = $("#main-nav");
    var $links = $nav.find("a");
    var cookie_namespace = "cehd-edpsych";
    var cookie_name = cookie_namespace + "-navOpen";
    var cookie_options = {
        path: "/"
    };

    $open_button.on("click", function() {
        $nav.addClass("active");
        cookies.set(cookie_name, true, cookie_options);
    });

    $close_button.on("click", function() {
        $nav.removeClass("active");
        cookies.set(cookie_name, false, cookie_options);
    });

    $links.each(function(index, link) {
        if (link.href === window.location.href) {
            $(link).addClass("current");
        }
    });
});
