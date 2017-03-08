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

jquerybridget("isotope", isotope, $);

// now you can use $().isotope()

var $isotope = $(".isotope-container");

$isotope.isotope({
    itemSelector: ".isotope-item",
    layoutMode: "fitRows"
});

var $category_checkboxes = $(".isotope-select");

function filter_cards($filters) {
    var filters = "";

    $filters.each(function() {
        var $filter = $(this);

        if ($filter.is(":checked")) {
            filters += $filter.data("filter");
        }
    });

    $isotope.isotope({ filter: filters });
}

$category_checkboxes.on("change", function(e) {
    var $filter = $(this);
    var filterValue = $filter.data("filter");
    var $filters = $(".isotope-select:checked");

    // @todo make "show all" checked if no boxes are checked
    if (filterValue === "*") {
        // uncheck all the filters
        $filters.prop("checked", false);

        // check this one
        $(e.target).prop("checked", true);

        // filter on "*" (show everything)
        $isotope.isotope({ filter: filterValue });
    } else if ($filters.length > 0) {
        filter_cards($filters);
    } else {
        $(".category-select").prop("checked", false);
        $("#show-all-categories").prop("checked", true);
        $isotope.isotope({ filter: "*" });
    }
});

window.$ = $;
