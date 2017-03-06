"use strict";

var $ = require("jquery");
var isotope = require("isotope-layout");
var jquerybridget = require("jquery-bridget");
var $tracking_elements = $("[data-ga-command]");

// TODO load modules and scope them within an app. I.e., App.Renderer, App.Client

$(".no-js").removeClass("no-js");

$tracking_elements.on("click", function(event) {
    event.preventDefault();
    var $element = $(this);
    var command = $element.data("ga-command");
    var hit_type = $element.data("ga-hit-type");
    var category = $element.data("ga-category");
    var action = $element.data("ga-action");
    var label = $element.data("ga-label");
    var href = window.Helpers.getHref($element);

    window.ga(command, hit_type, category, action, label);

    setTimeout(function() {
            location.href = href;
        },
    400);
});

jquerybridget("isotope", isotope, $ );

// now you can use $().isotope()

var $isotope = $(".isotope-container");

$isotope.isotope({
    itemSelector: ".isotope-item",
    layoutMode: "fitRows"
});

$(".filter-button-group").on( "click", "button", function() {
    var filterValue = $(this).data("filter");
    $isotope.isotope({ filter: filterValue });
});

window.$ = $;
