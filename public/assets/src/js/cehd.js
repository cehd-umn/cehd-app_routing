"use strict";

var $ = require("jquery");
var queryString = require("query-string");
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

$.fn.extend({
    uncheckAll: function() {
        return this.each(function() {
            this.checked = false;
        });
    }
});

$(".nav-toggle").on("click touch", function() {
    var $nav = $("nav");
    $nav.toggleClass("open");
});

var HistoryManager = function() {
    this.makeQueryString = function(params_data) {
        var query = "";
        var params = "";
        if (params_data.length > 0) {
            query = "?";
            params = $.param(params_data);
        }

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

    this.pushState = function(url) {
        history.pushState(null, null, url);
    };
};

var FilterUIManager = function() {
    this.setChecked = function(element_selector, checked) {
        $(element_selector).prop("checked", checked);
    };

    /**
     *
     * @param filters - string for use in isotope
     * @param query_data - object - an object containing filter data
     * @param $cardsContainer - A JQuery object representing the cards container
     * @param $clearFiltersInput - A JQuery object representing the "No filters" button
     */
    this.manageFilterState = function(filters, query_data, $cardsContainer, $clearFiltersInput) {
        var no_filters_should_be_checked = filters.length === 0;
        var self = this;
        var $all_buttons = $(".card-select");

        $all_buttons.uncheckAll();

        $.each(query_data.filters, function(k, v) {
            var element_selector = "#" + v + "-filter";
            self.setChecked(element_selector, true);
        });

        $clearFiltersInput.prop("checked", no_filters_should_be_checked);
    };
};

var FilterManager = function(cardContainerSelector, filterInputSelector, searchInputSelector) {
    var self = this;

    this.filterInputSelector = filterInputSelector;
    this.clearFiltersInputSelector = "#clear-filters";
    this.historyManager = new HistoryManager();
    this.uiManager = new FilterUIManager();
    this.$cardsContainer = $(cardContainerSelector);
    this.$filterInputs = $(filterInputSelector);
    this.$clearFiltersInput = $(this.clearFiltersInputSelector);
    this.$searchInput = $(searchInputSelector);

    this.$searchInput.on("keyup", function() {
        var search_value = $(this).val();
        var search_result = self.search.search(search_value, {expand: true});

        self.$filterInputs.uncheckAll();
        self.$clearFiltersInput.prop("checked", true);
        self.clearQueryParams();

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

    this.init_search = function(elements_selector) {
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

    this.filterCards = function(filter_classes) {

        var filters = filter_classes.split(".").slice(1);

        $(".hidden").removeClass("hidden");

        $.each(filters, function(k, v) {

            if (v === "*" || v === "") {
                $(".hidden").removeClass("hidden");
            } else {
                var $elements = self.$cardsContainer.find(":not(." + v + ")");

                $elements.each(function() {
                    $(this).addClass("hidden");
                });
            }
        });
    };

    /**
     * @param object $filterInputs - A JQuery object containing filter checkbox elements
     * @return string - A string of CSS classes suitable for Isotope filtering
     */
    this.getClassStr = function($filterInputs) {
        var class_str = "";
        $filterInputs.each(function() {
            var $filter = $(this);
            // the data element should contain the correct selector
            var filter_value = $filter.data("filter");
            class_str += filter_value;
        });
        return class_str;
    };

    /**
     * @param string selector - a JQuery selector
     * @return object - a JQuery object matching the selector that is also checked
     */
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

    this.clearQueryParams = function() {
        this.historyManager.pushState(window.location.pathname);
    };

    // This sets the UI state on page load
    this.setUIState = function() {
        var filters = this.loadQueryParams();
        var query_data = this.getQueryData();
        this.uiManager.manageFilterState(filters, query_data, this.$cardsContainer, this.$clearFiltersInput);
        this.filterCards(filters);
    };

    this.$clearFiltersInput.on("change", function() {
        self.clearQueryParams();
    });

    this.$clearFiltersInput.on("change", function() {
        var $input = $(this);

        if ($input.is(":checked")) {
            self.$filterInputs.uncheckAll();
            self.filterCards($input.data("filter"));
        }
    });

    // This needs to manage the UI state and the browser history state
    this.$filterInputs.on("change", function() {
        var $selected_cards = self.getSelected(self.filterInputSelector);
        var filters = self.getClassStr($selected_cards);
        var query_data = self.historyManager.cssClassesToParams(filters);
        var url = self.historyManager.makeURL(query_data);
        var clear_filters_should_be_checked = $selected_cards.length === 0;

        // Send data off to Google Analytics
        // @todo - concatenate this info and send it in one request
        $selected_cards.each(function() {
            var command = "send";
            var hit_type = "event";
            var category = $(this).data("ga-category");
            var action = "filter";
            var label = category + "-filter";
            window.ga(command, hit_type, category, action, label);
        });

        self.$clearFiltersInput.prop("checked", clear_filters_should_be_checked);
        self.historyManager.pushState(url);
        self.filterCards(filters);
    });

    this.search = this.init_search(".card");
};

window.$ = $;
window.FilterManager = FilterManager;
