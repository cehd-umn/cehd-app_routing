const base = require("../base.js");

class CEHDSVG extends base {
    validateAttrs (expected, actual) {
        return this.containsExpectedKeys(expected, actual);
    }
}

module.exports = CEHDSVG;
