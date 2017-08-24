"use strict";
var $ = require("jquery");

var GA = function() {
    this.getElementsWithGAData = function($target) {
        var $tracking_elements = $target.find("[data-ga-command]");
        return $tracking_elements;
    };

    this.trackingElementClickHandler = function(event) {
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
    };
};

window.GA = GA;
