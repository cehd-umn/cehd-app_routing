"use strict";
var $ = require("jquery");

var HTTPClient = function() {

    this.get = function(params) {
        var self = this;
        self.results = null;

        return $.ajax({
            url: params.url,
            dataType: "json",
            data: params.data,
            success: function(data) {
                self.results = data;
            },
            error: function() {}
        });
    };

    this.getResults = function() {
        return this.results;
    };

    return this;
};

window.HTTPClient = HTTPClient;
