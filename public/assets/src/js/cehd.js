"use strict";

var $ = require("jquery");
var queryString = require("query-string");
var isotope = require("isotope-layout");
var jquerybridget = require("jquery-bridget");
var $tracking_elements = $("[data-ga-command]");
var elasticlunr = require("elasticlunr");

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

var search_setup = function(elements_selector) {
    var $elements = $(elements_selector);

    var search = elasticlunr(function() {
        this.addField("title");
        this.addField("description");
        this.addField("categories");
        this.setRef("id");
    });

    $elements.each(function(index, element) {

        var $element = $(element);
        var title = $element.find(".title").text();
        var description = $element.find(".description").text();
        var categories = $element.data("categories");
        var id = $element.attr("id");
        var doc = {
            id: id,
            title: title,
            description: description,
            categories: categories
        };

        search.addDoc(doc);
    });

    return search;
};

var FilterManager = function(card_container_selector, filter_input_selector, search_input_selector, isotope_params) {
    var self = this;

    this.historyManager = new HistoryManager();
    this.uiManager = new FilterUIManager();
    this.$cards = $(card_container_selector);
    this.$filter_inputs = $(filter_input_selector);
    this.search = search_setup(isotope_params.itemSelector);
    this.$search_input = $(search_input_selector);

    this.$search_input.on("keyup", function() {
        var search_value = $(this).val();
        var search_result = self.search.search(search_value, {expand: true});

        $(".full-text-search-result").removeClass("full-text-search-result");

        $.each(search_result, function() {
            var id = this.ref;
            var selector = "#" + id;
            $(selector).addClass("full-text-search-result");
        });

        if (search_value.length > 0) {
            self.filterCards(".full-text-search-result");
        } else {
            self.filterCards("*");
        }
    });

    // Instantiate isotope
    this.$cards.isotope(isotope_params);

    this.filterCards = function (filters) {
        this.$cards.isotope({ filter: filters });
    };

    this.formClassStr = function($filter_inputs) {
        var class_str = "";
        $filter_inputs.each(function() {
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
        var query_data = queryString.parse(search);

        // Make sure filters are always an array
        if (query_data.filters && query_data.filters.constructor !== Array) {
            var cached_filter = query_data.filters;
            query_data.filters = [];
            query_data.filters.push(cached_filter);
        }

        return query_data;
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

    // @xxx this needs a better name
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

    this.$filter_inputs.on("change", function() {
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
