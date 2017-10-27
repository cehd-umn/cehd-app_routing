const queryString = require('query-string');
const $ = require('jquery');
const historyManager = require("./historyManager.js");

class FilterManager {

    constructor(tocContainerSelector, filterInputSelector) {
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
            self.filterElements(filters);
            self.historyManager.pushState(url);
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
                console.log(selector);
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
}

module.exports = FilterManager;
