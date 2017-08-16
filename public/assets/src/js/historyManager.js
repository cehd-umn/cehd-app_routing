class HistoryManager {
    makeQueryString (params_data) {
        let query = "";
        let params = "";
        if (params_data.length > 0) {
            query = "?";
            params = $.param(params_data);
        }

        return query + params;
    }

    makeURL (params_data) {
        let query_str = this.makeQueryString(params_data);
        return window.location.pathname + query_str;
    }

    cssClassesToArray (classes) {
        return classes.split(".");
    }

    cssClassesToParams (classes) {
        const values = this.cssClassesToArray(classes);
        let results = [];

        $.each(values, function(k, v) {
            if (v.length > 0) {
                results.push({
                    name: "filters",
                    value: v
                });
            }
        });

        return results;
    }

    pushState (url) {
        history.pushState(null, null, url);
    }
}

module.exports.historyManager = HistoryManager;
