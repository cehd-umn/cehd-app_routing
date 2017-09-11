"use strict";

var $ = require("jquery");
var cookies = require("js-cookie");

var $open_button = $("#menuicon-wrapper");
var $close_button = $("#navhide");
var $nav = $("#main-nav");
var $links = $nav.find("a");
var cookie_namespace = "cehd-edpsych";
var cookie_name = cookie_namespace + "-navOpen";
var cookie_options = {
    path: "/"
};
var $tracking_elements = $("[data-ga-command]");

// TODO load modules and scope them within an app. I.e., App.Renderer, App.Client

$(".no-js").removeClass("no-js");

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

$tracking_elements.on("click", function(event) {
    event.preventDefault();
    var $element = $(this);
    var command = $element.data("ga-command");
    var hit_type = $element.data("ga-hit-type");
    var category = $element.data("ga-category");
    var action = $element.data("ga-action");
    var label = $element.data("ga-label");
    var href = window.Helpers.getHref($element);

    window.ga(command, hit_type, category, action, label, {
        hitCallback: function () {
            location.href = href;
        }
    });
});

window.$ = $;
