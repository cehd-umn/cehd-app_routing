class Search {

    constructor(base_url) {
        this.params = null;
        this.base_url = base_url + "/services/search/";
    }

    getParams(params) {
        if (typeof params.start === "undefined") {
            params.start = 0;
        }

        if (typeof params.num === "undefined") {
            params.num = 10;
        }

        const request_params = {
            url: this.base_url,
            data: params
        };

        return request_params;
    }
}

module.exports = Search;
