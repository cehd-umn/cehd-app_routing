const CEHDSVG = require("./svg.js");

class CEHDSVGText extends CEHDSVG {
    constructor() {
        super();
        this.expectedAttrs = {
            "textX": "string",
            "textY": "string",
            "textFill": "string",
        };
    }

    validateViewboxAttrs(actual) {
        return this.validateAttrs(this.expectedAttrs, actual);
    }

    getAttrsFromData(data) {

        const expectedAttrNames = Object.keys(this.expectedAttrs);

        // In case we didn't validate the attrs beforehand
        expectedAttrNames.forEach(function(name) {
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

    // create (svgjsInstance, x, y, width, height) {
    //     return svgjsInstance.viewbox(x, y, width, height);
    // }
}

module.exports = CEHDViewbox;
