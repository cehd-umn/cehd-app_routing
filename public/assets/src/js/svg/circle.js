const CEHDSVG = require("./svg.js");

class CEHDCircle extends CEHDSVG {
    validateCircleAttrs(actual) {
        const expected = {
            "fill": "string",
            "cx": "string or number",
            "cy": "string or number"
        };

        return this.validateAttrs(expected, actual);
    }
}

module.exports = CEHDCircle;
