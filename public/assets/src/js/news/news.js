const http_client = require("../http_client.js");

/**
* Creates a new News class
* @class
* @author thoma127
*/
class News {

    /**
     * @function
     * @description generic method for making aysnchronous requests
     * @borrows HTTPClient as httpClient
     * @param url - string - URL for the http client $.ajax request
     * @param data - object - data for the http client $.ajax request
     * @returns object containing the JQuery $.ajax object and the http client
     */
    getNews(url, data) {
        const request_params = {
            "url": url,
            "data": data
        };
        const httpClient = new http_client();
        const request = httpClient.get(request_params);

        return {
            "request": request,
            "client": httpClient
        };
    }
}

module.exports = News;
