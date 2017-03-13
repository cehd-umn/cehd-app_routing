"use strict";

var $ = require("jquery");
var queryString = require("query-string");
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

var HistoryManager = function() {
    this.makeQueryString = function(params_data) {
        var query = "?";
        var params = $.param(params_data);
        return query + params;
    };

    this.makeURL = function(params_data) {
        var query_str = this.makeQueryString(params_data);
        return window.location.pathname + query_str;
    };

    this.cssClassesToArray = function(classes) {
        return classes.split(".");
    };

    this.cssClassesToParams = function(classes) {
        var values = this.cssClassesToArray(classes);
        var results = [];

        $.each(values, function(k, v) {
            if (v.length > 0) {
                results.push({
                    name: "filters",
                    value: v
                });
            }
        });

        return results;
    };

    this.pushState =function(url) {
        history.pushState(null, null, url);
    };
};

var FilterUIManager = function() {
    this.setChecked = function(element_selector) {
        $(element_selector).prop("checked", true);
    };
};

// @todo keep track of these filters are query params and load them on page load
var FilterManager = function(card_container_selector, input_selector, isotope_params) {
    var self = this;

    this.historyManager = new HistoryManager();
    this.uiManager = new FilterUIManager();
    this.$cards = $(card_container_selector);
    this.$inputs = $(input_selector);

    // Instantiate isotope
    this.$cards.isotope(isotope_params);

    this.filterCards = function (filters) {
        this.$cards.isotope({ filter: filters });
    };

    this.formClassStr = function($inputs) {
        var class_str = "";
        $inputs.each(function() {
            var $filter = $(this);
            var filter_value = $filter.data("filter");
            class_str += filter_value;
        });
        return class_str;
    };

    this.getSelected = function(selector) {
        return $(selector + ":checked");
    };

    this.getQueryData = function() {
        var search = window.location.search;
        return queryString.parse(search);
    };

    this.loadQueryParams = function() {
        var query_data = this.getQueryData();
        var filters = "";

        if (typeof query_data.filters !== "undefined" && query_data.filters.length > 0) {
            filters = query_data.filters.join(".");
            filters = "." + filters;
        }
        return filters;
    };

    this.onPageLoad = function() {
        var filters = this.loadQueryParams();
        var query_data = this.getQueryData();
        var self = this;

        if (filters.length > 0) {
            this.$cards.isotope({ filter: filters });

            $.each(query_data.filters, function(k, v) {
                var element_selector = "#" + v + "-filter";
                self.uiManager.setChecked(element_selector);
            });
        }
    };

    this.$inputs.on("change", function() {
        var $filters = self.getSelected(".isotope-select");
        var filters = self.formClassStr($filters);
        var params_data = self.historyManager.cssClassesToParams(filters);
        var url = self.historyManager.makeURL(params_data);

        self.historyManager.pushState(url);
        self.filterCards(filters);
    });
};

window.$ = $;
window.FilterManager = FilterManager;
