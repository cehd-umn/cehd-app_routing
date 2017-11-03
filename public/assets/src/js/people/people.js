const http_client = require("../http_client.js");

/**
* Creates a new People class
* @class
* @author thoma127
*/
class People {

    /**
     * @function
     * @description generic method for making aysnchronous requests
     * @borrows HTTPClient as httpClient
     * @param url - string - URL for the http client $.ajax request
     * @param data - object - data for the http client $.ajax request
     * @returns object containing the JQuery $.ajax object and the http client
     */
    get(url, data) {
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

    /**
     * @function
     * @description generic method for making aysnchronous requests for a list of people
     * @param url - string - URL for the http client $.ajax request
     * @param tg - string or int - People tool tag id number
     * @returns object containing the JQuery $.ajax object and the http client
     */
    getPeople(url, tg) {
        const data = {
            "tg": tg
        };

        return this.get(url, data);
    }

    /**
     * @function
     * @description generic method for making aysnchronous requests for a specific person
     * @param url - string - URL for the http client $.ajax request
     * @param internet_id - string - internetID for the person
     * @returns object containing the JQuery $.ajax object and the http client
     */
    getPerson(url, internet_id) {
        const data = {
            "internetId": internet_id
        };

        return this.get(url, data);
    }
}

module.exports = People;
