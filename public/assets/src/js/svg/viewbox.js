const CEHDSVG = require("./svg.js");

class CEHDViewbox extends CEHDSVG {

    validateViewboxAttrs(actual) {
        return this.validateAttrs(actual);
    }

    getAttrsFromData(data) {

        // In case we didn't validate the attrs beforehand
        this.expectedAttrNames.forEach(function(name) {
            if (!name in data) {
            }
        });

        return {
            x: data.viewboxX,
            y: data.viewboxY,
            width: data.viewboxWidth,
            height: data.viewboxHeight
        };
    }
}

module.exports = CEHDViewbox;
