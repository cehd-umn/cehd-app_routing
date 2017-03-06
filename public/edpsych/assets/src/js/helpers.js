var moment = require("moment");
var $ = require("jquery");
"use strict";

var Helpers = {
    parseEvents: function(events) {
        var event_data = {};

        if (parseInt(events["@attributes"].totalCount) > 1) {
            $.each(events.event, function() {
                "use strict";
                var event_date = moment(this.dateISO);
                var month = event_date.format("MMMM");
                var day = event_date.format("D");
                var event_obj = {};

                event_obj.month = month;
                event_obj.day = day;
                event_obj.link = this.link;
                event_obj.location = this.location;
                event_obj.title = this.title;

                if (typeof event_data[month] === "undefined") {
                    event_data[month] = [];
                }

                event_data[month].push(event_obj);
            });
        } else if (parseInt(events["@attributes"].totalCount) > 0) {
            var event_date = moment(events.event.dateISO);
            var month = event_date.format("MMMM");
            var day = event_date.format("D");
            var event_obj = {};

            event_obj.month = month;
            event_obj.day = day;
            event_obj.link = events.event.link;
            event_obj.location = events.event.location;
            event_obj.title = events.event.title;

            if (typeof event_data[month] === "undefined") {
                event_data[month] = [];
            }

            event_data[month].push(event_obj);
        }
        return event_data;
    },

    feedPathToLinkPath: function(feed_path) {
        return feed_path.replace("feed/", "");
    },

    getHref: function($element) {
        href = (typeof($element.attr("href")) !== "undefined") ? $element.attr("href") : "";
        return href;
    }
};

window.Helpers = Helpers;
