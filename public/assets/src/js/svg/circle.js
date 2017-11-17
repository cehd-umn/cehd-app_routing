const CEHDSVG = require("./svg.js");

class CEHDCircle extends CEHDSVG {

    validateCircleAttrs(actual) {
        return this.validateAttrs(actual);
    }

    getCircleAttrsFromData(data) {

        // In case we didn't validate the attrs beforehand
        this.expectedAttrNames.forEach(function(name) {
            if (!name in data) {
                console.warning("Missing " + name + " in circle attributes");
            }
        });

        return {
            fill: data.circleBgColor,
            cx: data.circleCenterX,
            cy: data.circleCenterY,
            size: data.circleSize
        };
    }
}

module.exports = CEHDCircle;
