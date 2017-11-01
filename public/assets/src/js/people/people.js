// const $ = require("jquery");
const http_client = require("../http_client.js");
// const renderer = require("../renderer.js");
// const helpers = require("../helpers.js");

/**
* Creates a new People class
* @class
* @author thoma127
*/
class People {

    // constructor () {
    //     this.results = null;
    // }

    /**
     * @function
     * @description generic method for making aysnchronous requests
     * @borrows HTTPClient as http_client
     * @param url - string - URL for the http client $.ajax request
     * @param data - object - data for the http client $.ajax request
     * @returns JQuery $.ajax object
     */
    get(url, data) {
        const request_params = {
            "url": url,
            "data": data
        };
        // const self = this;
        const httpClient = new http_client();
        const request = httpClient.get(request_params);

        return {
            "request": request,
            "client": httpClient
        };
    }

    getPeople(url, tg) {
        const data = {
            "tg": tg
        };

        return this.get(url, data);
    }

    getPerson(url, internet_id) {
        const data = {
            "internetId": internet_id
        };

        return this.get(url, data);
    }

    // render(target_element_selector, template_name, template_path) {
    //     const renderer = new renderer();
    // }

    // getResults() {
    //     return this.results;
    // }
}

module.exports = People;
