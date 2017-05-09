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
        var $all_cards = $(".isotope-select");

        $all_cards.uncheckAll();

        $.each(query_data.filters, function(k, v) {
            var element_selector = "#" + v + "-filter";
            self.setChecked(element_selector, true);
        });

        $clearFiltersInput.prop("checked", no_filters_should_be_checked);
    };
};

var FilterManager = function(cardContainerSelector, filterInputSelector, searchInputSelector, isotopeParams) {
    var self = this;

    this.filterInputSelector = filterInputSelector;
    this.clearFiltersInputSelector = "#clear-filters";
    this.historyManager = new HistoryManager();
    this.uiManager = new FilterUIManager();
    this.$cardsContainer = $(cardContainerSelector);
    this.$filterInputs = $(filterInputSelector);
    this.$clearFiltersInput = $(this.clearFiltersInputSelector);
    this.$searchInput = $(searchInputSelector);
    this.filterButtons = [];

    this.$filterInputs.each(function() {
        var button = {checked: this.checked, isClearFiltersInput: false};
        var obj = {};
        obj[this.id] = button;
        self.filterButtons.push(obj);
    });

    // The clear-filters button is a separate case
    var button = {checked: this.$clearFiltersInput.checked, isClearFiltersInput: true};
    var obj = {};
    var clear_filters_id = this.$clearFiltersInput.id;
    obj[clear_filters_id] = button;
    self.filterButtons.push(obj);

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

    // Instantiate isotope
    this.$cardsContainer.isotope(isotopeParams);

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

    this.filterCards = function (filters) {
        this.$cardsContainer.isotope({ filter: filters });
    };

    this.getClassStr = function($filterInputs) {
        var class_str = "";
        $filterInputs.each(function() {
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

    this.clearQueryParams = function() {
        var pathname = window.location.pathname;
        this.historyManager.pushState(pathname);
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
        var $selected_cards = self.getSelected(".isotope-select");
        var filters = self.getClassStr($selected_cards);
        var query_data = self.historyManager.cssClassesToParams(filters);
        var url = self.historyManager.makeURL(query_data);

        if($selected_cards.length > 0) {
            self.$clearFiltersInput.prop("checked", false);
        }

        self.historyManager.pushState(url);
        self.filterCards(filters);
    });

    this.search = this.init_search(isotopeParams.itemSelector);
};

window.$ = $;
window.FilterManager = FilterManager;
