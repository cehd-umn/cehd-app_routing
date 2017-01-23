"use strict";


var Helpers = {
    parseEvents: function(events) {
        if (parseInt(events["@attributes"].totalCount) > 0) {
            // events come back in chronological order so reverse the array
            events.event.reverse();
        } else {
            events.event = [];
        }

        return events.event;
    }
};

window.Helpers = Helpers;
