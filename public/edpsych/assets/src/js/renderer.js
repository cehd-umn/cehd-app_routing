"use strict";

var nunjucks = require("nunjucks");
var $ = require("jquery");

var Renderer = {
    defaults: {
        template_path: "../views",
        $target_element: $("#people-tool-container")
    },

    settings: null,

    init: function(params) {
        this.settings = this._mergeOptions(params);
        nunjucks.configure(this.settings.template_path, { autoescape: true });
    },

    // TODO improve performance by making this async? https://mozilla.github.io/nunjucks/api.html#asynchronous-support
    render: function() {
        this.settings.$target_element.html(
            nunjucks.render(this.settings.template_name,
                {
                    data: this.settings.data
                }
            )
        );
    },

    _mergeOptions: function(params) {
        return $.extend(this.defaults, params);
    }
};

window.Renderer = Renderer;
