"use strict";

import "babel-polyfill";
import $ from "jquery";
import filterManager from "./filterManager.js";
import helpers from "./helpers.js";
import renderer from "./renderer.js";
import http_client from "./http_client.js";
import search_client from "./search_client.js";

class App {
    constructor() {
        const $tracking_elements = $("[data-ga-command]");

        this.filterManager = filterManager;

        this.helpers = helpers;

        this.renderer = renderer;

        this.http_client = http_client;

        this.search_client = search_client;

        $(".nav-toggle").on("click touch", function() {
            const $nav = $("nav");
            $nav.toggleClass("open");
        });

        $tracking_elements.on("click", function(event) {
            event.preventDefault();
            const $element = $(this);
            const  command = $element.data("ga-command");
            const  hit_type = $element.data("ga-hit-type");
            const  category = $element.data("ga-category");
            const  action = $element.data("ga-action");
            const  label = $element.data("ga-label");
            const  href = window.Helpers.getHref($element);

            window.ga(command, hit_type, category, action, label, {
                hitCallback: function () {
                    location.href = href;
                }
            });
        });

        $(".no-js").removeClass("no-js");

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
}

window.App = App;
