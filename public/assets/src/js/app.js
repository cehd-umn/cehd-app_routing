require("babel-polyfill");
const $ = require("jquery");
const GA = require("./ga.js");
const helpers = require("./helpers.js");
const renderer =require("./renderer.js");
const http_client = require("./http_client.js");
const search_client = require("./search_client.js");

class App {
    constructor() {
        const $tracking_elements = $("[data-ga-command]");

        this.helpers = helpers;

        this.renderer = renderer;

        this.httpClient = http_client;

        this.searchClient = search_client;

        this.GA = GA;

        $(".nav-toggle").off().on("click touch", function(e) {
            e.preventDefault();
            const $nav = $("nav");
            $nav.toggleClass("open");
        });

        $tracking_elements.on("click", function(event) {
            event.preventDefault();

            const $element = $(this);
            const command = $element.data("ga-command");
            const hit_type = $element.data("ga-hit-type");
            const category = $element.data("ga-category");
            const action = $element.data("ga-action");
            const label = $element.data("ga-label");
            const helpers = new helpers();
            const href = helpers.getHref($element);

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
