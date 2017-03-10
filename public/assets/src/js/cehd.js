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

var FilterManager = function(card_container_selector, input_selector, isotope_params) {
    var self = this;
    this.input_selector = input_selector;
    this.$cards = $(card_container_selector);
    this.$inputs = $(input_selector);
    // @todo set this selector as a param
    this.$showAllCategoriesCheckbox = $("#show-all-categories");
    // @todo set this selector as a param
    this.$showAllDegreesCheckbox = $("#show-all-degrees");
    // @todo set this selector as a param
    this.$showAllCheckbox = $("#show-all");

    this.$showAlls = $(".is-show-all");

    this.$cards.isotope(isotope_params);

    this.filterCards = function ($inputs) {
        var filters = "";

        $inputs.each(function() {
            var $filter = $(this);
            var filter_value = $filter.data("filter");
            filters += filter_value;
        });

        this.$cards.isotope({ filter: filters });
    };

    this.getSelected = function(selector) {
        return $(selector + ":checked");
    };

    this.getCategoriesSelected = function() {
        // @todo set this selector as a param
        return this.getSelected(".category-select");
    };

    this.getDegreesSelected = function() {
        // @todo set this selector as a param
        return this.getSelected(".degree-select");
    };

    // change handler should only handle filtering and not ui state if possible
    this.$inputs.on("change", function() {
        var $filters = self.getSelected(".isotope-select");
        self.filterCards($filters);
    });
};

window.$ = $;
window.FilterManager = FilterManager;
