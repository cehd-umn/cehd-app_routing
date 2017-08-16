"use strict";
import $ from "jquery";

class GA {
    constructor() {
        return this;
    }

    getElementsWithGAData($target) {
        var $tracking_elements = $target.find("[data-ga-command]");
        return $tracking_elements;
    }

    trackingElementClickHandler(event) {
        event.preventDefault();
        const $element = $(this);
        const command = $element.data("ga-command");
        const hit_type = $element.data("ga-hit-type");
        const category = $element.data("ga-category");
        const action = $element.data("ga-action");
        const label = $element.data("ga-label");
        const href = window.Helpers.getHref($element);

        window.ga(command, hit_type, category, action, label);

        setTimeout(function() {
                location.href = href;
            },
        400);
    }
}

module.exports = GA;
