"use strict";

var nunjucks = require("nunjucks");
var $ = require("jquery");

var Renderer = function () {
    this.defaults = {
        template_path: "../views",
        $target_element: $("#results-container")
    };

    this.settings = null;

    this.env = null;

    this.init = function(params) {
        this.settings = this._mergeOptions(params);
        this.env = nunjucks.configure(this.settings.template_path, { autoescape: true });
        this.addFilters();
    };

    this.addFilters = function() {
        // Custom nunjucks filter
        // http://stackoverflow.com/questions/29844865/nunjucks-check-for-object-or-string
        this.env.addFilter("is_string", function(obj) {
            return typeof obj === "string";
        });

        this.env.addFilter("strip_style_attributes", function(str) {
            return str.replace(/style=(".*?"|'.*?'|[^"'][^\s]*)/g, "");
        });
    };

    // TODO improve performance by making this async? https://mozilla.github.io/nunjucks/api.html#asynchronous-support
    this.render = function(append) {
        var html = this.env.render(this.settings.template_name,
            {
                data: this.settings.data
            }
        );

        var function_name = "html";
        if (append) {
            function_name = "append";
        }
        this.settings.$target_element[function_name](html);
    };

    this._mergeOptions = function(params) {
        return $.extend(this.defaults, params);
    };
};

window.Renderer = Renderer;
