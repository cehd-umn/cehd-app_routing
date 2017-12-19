const elasticlunr = require("elasticlunr");
const filterUIManager = require("./filterUIManager.js");
const historyManager = require("./historyManager.js");
const queryString = require("query-string");
const $ = require("jquery");

class FilterManager {

    constructor(cardContainerSelector, filterInputSelector, searchInputSelector) {
        let self = this;
        this.filterInputSelector = filterInputSelector;
        this.clearFiltersInputSelector = "#clear-filters";
        this.historyManager = new historyManager();
        this.uiManager = new filterUIManager();
        this.$cardsContainer = $(cardContainerSelector);
        this.$filterInputs = $(filterInputSelector);
        this.$clearFiltersInput = $(this.clearFiltersInputSelector);
        this.$searchInput = $(searchInputSelector);
        this.search = this.init_search(".card");

        this.$searchInput.on("keyup", function() {
            const search_value = $(this).val();
            const search_result = self.search.search(search_value, {expand: true});

            self.$filterInputs.uncheckAll();
            self.$clearFiltersInput.prop("checked", true);
            self.clearQueryParams();

            $(".full-text-search-result").removeClass("full-text-search-result");

            $.each(search_result, function() {
                const id = this.ref;
                const selector = "#" + id;
                $(selector).addClass("full-text-search-result");
            });

            if (search_value.length > 0) {
                self.filterCards(".full-text-search-result");
            } else {
                self.filterCards("*");
            }
        });

        this.$clearFiltersInput.on("change", function() {
            self.clearQueryParams();
        });

        this.$clearFiltersInput.on("change", function() {
            const $input = $(this);

            if ($input.is(":checked")) {
                self.$filterInputs.uncheckAll();
                self.filterCards($input.data("filter"));
            }
        });

        // This needs to manage the UI state and the browser history state
        this.$filterInputs.on("change", function() {
            const $selected_cards = self.getSelected(self.filterInputSelector);
            const filters = self.getClassStr($selected_cards);
            const query_data = self.historyManager.cssClassesToParams(filters);
            const url = self.historyManager.makeURL(query_data);
            const clear_filters_should_be_checked = $selected_cards.length === 0;

            // Send data off to Google Analytics
            // @todo - concatenate this info and send it in one request
            $selected_cards.each(function() {
                const command = "send";
                const hit_type = "event";
                const category = $(this).data("ga-category");
                const action = "filter";
                const label = category + "-filter";
                //XXX this may not be working
                window.ga(command, hit_type, category, action, label);
            });

            self.$clearFiltersInput.prop("checked", clear_filters_should_be_checked);
            self.historyManager.pushState(url);
            self.filterCards(filters);
        });

    }

    init_search(elements_selector) {
        const $elements = $(elements_selector);
        const search = elasticlunr(function() {
            this.addField("title");
            this.addField("description");
            this.addField("categories");
            this.setRef("id");
        });

        $elements.each(function(index, element) {

            const $element = $(element);
            const title = $element.find(".title").text();
            const description = $element.find(".description").text();
            const categories = $element.data("categories");
            const id = $element.attr("id");
            const doc = {
                id: id,
                title: title,
                description: description,
                categories: categories
            };

            search.addDoc(doc);
        });

        return search;
    }

    filterCards(filter_classes) {

        const filters = filter_classes.split(".").slice(1);
        const self = this;

        $(".hidden").removeClass("hidden");
        $.each(filters, function(k, v) {
            if (v === "*" || v === "") {
                $(".hidden").removeClass("hidden");
            } else {
                let $elements = self.$cardsContainer.find(":not(." + v + ")");

                $elements.each(function() {
                    $(this).addClass("hidden");
                });
            }
        });
    }

    /**
     * @param object $filterInputs - A JQuery object containing filter checkbox elements
     * @return string - A string of CSS classes suitable for Isotope filtering
     */
    getClassStr($filterInputs) {
        let class_str = "";
        $filterInputs.each(function() {
            const $filter = $(this);
            // the data element should contain the correct selector
            const filter_value = $filter.data("filter");
            class_str += filter_value;
        });
        return class_str;
    }

    /**
     * @param string selector - a JQuery selector
     * @return object - a JQuery object matching the selector that is also checked
     */
    getSelected(selector) {
        return $(selector + ":checked");
    }

    getQueryData() {
        const search = window.location.search;
        let query_data = queryString.parse(search);

        // Make sure filters are always an array
        if (query_data.filters && query_data.filters.constructor !== Array) {
            const cached_filter = query_data.filters;
            query_data.filters = [];
            query_data.filters.push(cached_filter);
        }

        return query_data;
    }

    loadQueryParams() {
        const query_data = this.getQueryData();
        let filters = "";

        if (typeof query_data.filters !== "undefined" && query_data.filters.length > 0) {
            filters = query_data.filters.join(".");
            filters = "." + filters;
        }
        return filters;
    }

    clearQueryParams() {
        this.historyManager.pushState(window.location.pathname);
    }

    // This sets the UI state on page load
    setUIState() {
        const filters = this.loadQueryParams();
        const query_data = this.getQueryData();
        this.uiManager.manageFilterState(filters, query_data, this.$cardsContainer, this.$clearFiltersInput);
        this.filterCards(filters);
    }
}

window.FilterManager = FilterManager;
