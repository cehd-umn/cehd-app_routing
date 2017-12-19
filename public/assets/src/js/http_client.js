const $ = require("jquery");

class HTTPClient {

    constructor() {
        return this;
    }

    get(params) {
        const self = this;
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
    }

    getResults() {
        return this.results;
    }
}

module.exports = HTTPClient;
