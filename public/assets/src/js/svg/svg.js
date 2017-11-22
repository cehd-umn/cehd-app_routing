const base = require("../base.js");

class CEHDSVG extends base {
    /**
     *
     * Maps existing keys to keys for the svg.js framework.
     *
     * @param attrs - an object that maps a key to the correct svg.js key
     * @example { actualKey: "svgJsKey" }
     *
     */
    constructor(attrs) {
        super();

        const svgJsNames = [];
        this.keyMap = attrs;
        this.expectedAttrNames = Object.keys(attrs);

        this.expectedAttrNames.forEach(function(key) {
            svgJsNames.push(attrs[key]);
        });
        this.svgJsNames = svgJsNames;
    }

    validateAttrs(actual) {
        return this.containsExpectedKeys(this.keyMap, actual);
    }

    getAttrs(data) {
        const returnValues = {};
        const self = this;
        this.expectedAttrNames.forEach(function(name) {
            returnValues[self.keyMap[name]] = data[name];
        });
        return returnValues;
    }
}

module.exports = CEHDSVG;
