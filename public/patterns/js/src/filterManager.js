const queryString = require('query-string');
const $ = require('jquery');
const historyManager = require("./historyManager.js");

class FilterManager {

    constructor(tocContainerSelector, filterInputSelector, filterListContainerSelector) {
        let self = this;
        this.historyManager = new historyManager();
        this.filterInputSelector = filterInputSelector;
        this.$tocContainer = $(tocContainerSelector);
        this.$filterInputs = $(filterInputSelector);

        this.$filterInputs.on("change", function() {
            const $selected_elements = self.getSelected(self.filterInputSelector);
            const filters = self.getClassStr($selected_elements);
            const query_data = self.historyManager.cssClassesToParams(filters);
            const url = self.historyManager.makeURL(query_data);
            const filter_names = [];
            self.filterElements(filters);
            self.historyManager.pushState(url);

            $.each($selected_elements, function() {
                const label_text = $("label[for='" + $(this).attr('id') + "']").text();
                filter_names.push(label_text);
            });

            self.manageFilterList(filter_names, filterListContainerSelector);
        });

        /**
        * Adds an uncheckAll function to JQuery
        */
        $.fn.extend({
            uncheckAll: function() {
                return this.each(function() {
                    this.checked = false;
                });
            }
        });
    }

    filterElements(filter_classes) {
        const filters = filter_classes.split(".").slice(1);
        const self = this;

        $(".hidden").removeClass("hidden");
        $.each(filters, function(k, v) {
            if (v === "*" || v === "") {
                $(".hidden").removeClass("hidden");
            } else {
                const selector = ":not(.p-" + v + ")";
                const $elements = self.$tocContainer.find(selector);

                $elements.each(function() {
                    $(this).addClass("hidden");
                });
            }
        });
    }

    /**
     * @param string selector - a JQuery selector
     * @return object - a JQuery object matching the selector that is also checked
     */
    getSelected(selector) {
        return $(selector + ":checked");
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

    getQueryData(search) {
        let query_data = queryString.parse(search);

        // Make sure filters are always an array
        if (query_data.filters && query_data.filters.constructor !== Array) {
            const cached_filter = query_data.filters;
            query_data.filters = [];
            query_data.filters.push(cached_filter);
        }

        return query_data;
    }

    loadQueryParams(search) {
        const query_data = this.getQueryData(search);
        let filters = "";

        if (typeof query_data.filters !== "undefined" && query_data.filters.length > 0) {
            filters = query_data.filters.join(".");
            filters = "." + filters;
        }
        return filters;
    }

    setUIState(search, all_checkbox_selector) {
        const filters = this.loadQueryParams(search);
        const query_data = this.getQueryData(search);
        this.manageFilterState(filters, query_data, all_checkbox_selector);
        this.filterElements(filters);
    }

    setChecked(element_selector, checked) {
        $(element_selector).prop("checked", checked);
    }

    /**
     *
     * @param filters - string for use in isotope
     * @param query_data - object - an object containing filter data
     * @param all_checkbox_selector - string - class selector to target all checkboxes
     */
    manageFilterState(filters, query_data, all_checkbox_selector) {
        const no_filters_should_be_checked = filters.length === 0;
        const self = this;
        const $all_buttons = $(all_checkbox_selector);

        $all_buttons.uncheckAll();

        $.each(query_data.filters, function(k, v) {
            const element_selector = "#" + v + "-filter";
            self.setChecked(element_selector, true);
        });

    }

    manageFilterList(filter_names, container_selector) {
        const $container = $(container_selector);
        $container.text(filter_names.join(", "));
        console.log(filter_names, container_selector);
    }
}

module.exports = FilterManager;
