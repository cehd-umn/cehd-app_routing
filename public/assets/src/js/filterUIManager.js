const $ = require("jquery");

class FilterUIManager {

    setChecked(element_selector, checked) {
        $(element_selector).prop("checked", checked);
    }

    /**
     *
     * @param filters - string for use in isotope
     * @param query_data - object - an object containing filter data
     * @param $cardsContainer - A JQuery object representing the cards container
     * @param $clearFiltersInput - A JQuery object representing the "No filters" button
     */
    manageFilterState(filters, query_data, $cardsContainer, $clearFiltersInput) {
        var no_filters_should_be_checked = filters.length === 0;
        var self = this;
        var $all_buttons = $(".card-select");

        $all_buttons.uncheckAll();

        $.each(query_data.filters, function(k, v) {
            var element_selector = "#" + v + "-filter";
            self.setChecked(element_selector, true);
        });

        $clearFiltersInput.prop("checked", no_filters_should_be_checked);
    }
}

module.exports = FilterUIManager;
