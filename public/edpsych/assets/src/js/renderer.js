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
    };

    // TODO improve performance by making this async? https://mozilla.github.io/nunjucks/api.html#asynchronous-support
    this.render = function(append) {
        if (!append) {
            this.settings.$target_element.html(
                this.env.render(this.settings.template_name,
                    {
                        data: this.settings.data
                    }
                )
            );
        } else {
            this.settings.$target_element.append(
                this.env.render(this.settings.template_name,
                    {
                        data: this.settings.data
                    }
                )
            );
        }
    };

    this._mergeOptions = function(params) {
        return $.extend(this.defaults, params);
    };
};

window.Renderer = Renderer;
