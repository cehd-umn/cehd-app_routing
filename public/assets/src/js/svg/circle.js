const CEHDSVG = require("./svg.js");

class CEHDCircle extends CEHDSVG {
    constructor() {
        super();
        this.expectedAttrs = {
            "circleBgColor": "string",
            "circleCenterX": "string or number",
            "circleCenterY": "string or number"
        };
    }

    validateCircleAttrs(actual) {
        return this.validateAttrs(this.expectedAttrs, actual);
    }

    getCircleAttrsFromData(data) {

        const expectedAttrNames = Object.keys(this.expectedAttrs);

        // In case we didn't validate the attrs beforehand
        expectedAttrNames.forEach(function(name) {
            if (!name in data) {
                console.warning("Missing " + name + " in circle attributes");
            }
        });

        return {
            fill: data.circleBgColor,
            cx: data.circleCenterX,
            cy: data.circleCenterY
        };
    }
}

module.exports = CEHDCircle;
