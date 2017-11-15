// const $ = require("jquery");
const base = require("../base.js");

class CEHDSVG extends base {
    validateAttrs (expected, actual) {
        return this.arrayContainsExpected(expected, actual);
    }
}

module.exports = CEHDSVG;
