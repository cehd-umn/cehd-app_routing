const $ = require("jquery");
const assert = require("assert");
const baseClass = require("../base.js");
const CEHDSVG = require("../svg/svg.js");

const baseInstance = new baseClass();

describe("Base", function() {
    describe("arrayContainsExpected", function() {
        it("should return false if an actual does not contain all expected values", function() {
            let expected = [1, 4];
            let actual = [1, 3];
            assert.equal(false, baseInstance.arrayContainsExpected(expected, actual));
        });

        it("should return true if an arrays contain the same values in a different order", function() {
            let expected = [1, 4];
            let actual = [4, 1];
            assert.equal(true, baseInstance.arrayContainsExpected(expected, actual));
        });

        it("should recurse through nested arrays", function() {
            let expected = [1, 4, ["foo", "bar"]];
            let actual = [1, 4, ["foo", "bar"]];
            assert.equal(true, baseInstance.arrayContainsExpected(expected, actual));
        });

        it("should fail if nested arrays don't match", function() {
            let expected = [1, 4, ["foo", "bar"]];
            let actual = [1, 4, ["foo", "bat"]];
            assert.equal(false, baseInstance.arrayContainsExpected(expected, actual));
        });
    });

    describe("containsExpectedKeys", function() {
        it("should return false if an actual does not contain all expected keys", function() {
            let expected = {"foo": 1, "bar": 4};
            let actual = {"foo": 1, "bat": 3};
            assert.equal(false, baseInstance.containsExpectedKeys(expected, actual));
        });

        it("should return true if an actual does contain all expected keys regardless of order", function() {
            let expected = {"foo": 1, "bar": 4};
            let actual = {"bar": 1, "foo": 3};
            assert.equal(true, baseInstance.containsExpectedKeys(expected, actual));
        });

        it("should pass with nested arrays of objects", function() {
            let expected = {
                "foo": 1,
                "bar": 4,
                "args": [{
                    "baz": "bat"
                    },
                    {
                    "b": "c"
                    }]};

            let actual = {
                "bar": 1,
                "foo": 3,
                "args": [{
                    "baz": "bat"
                    },
                    {"b": "c"
                }]};
            assert.equal(true, baseInstance.containsExpectedKeys(expected, actual));
        });

        it("should return true if an actual does contain all expected keys regardless of order", function() {
            let expected = {"foo": 1, "bar": 4};
            let actual = {"bar": 1, "foo": 3};
            assert.equal(true, baseInstance.containsExpectedKeys(expected, actual));
        });

        it("should return true if an actual value is falsey", function() {
            let expected = {"foo": 1, "bar": 4, "baz": 3};
            let actual = {"bar": 0, "foo": false, "baz": null};
            assert.equal(true, baseInstance.containsExpectedKeys(expected, actual));
        });
    });
});

describe("CEHDSVG", function() {
    const attrMap = {
            "viewboxX": "x",
            "viewboxY": "y",
            "viewboxWidth": "width",
            "viewboxHeight": "height"
        };
    const attrs = {
            "viewboxX": 0,
            "viewboxY": 0,
            "viewboxWidth": 1400,
            "viewboxHeight": 250,
            "foo": "bar"
        };
    const expectedNumberOfAttrs = Object.keys(attrMap).length;
    const cehdSvg = new CEHDSVG(attrMap);
    const newAttrs = cehdSvg.getAttrs(attrs);

    describe("validateAttrs", function() {

        it("should return true if all expected attrs defined in the map are present", function() {
            assert.equal(true, cehdSvg.validateAttrs(attrs));
        });

        it("should return false if an expected attrs is missing", function() {
            const attrs = {
                    "viewboxX": 0,
                    "viewboxWidth": 1400,
                    "viewboxHeight": 250,
                    "foo": "bar"
                };
            assert.equal(false, cehdSvg.validateAttrs(attrs));
        });
    });

    describe("getAttrs", function() {
        it("should return only the attrs defined in the attrMap", function() {
            assert.equal(expectedNumberOfAttrs, Object.keys(newAttrs).length); // foo doesn't get sent back
        });

        it("should return the correct values with keys that match the keys in the attrMap", function() {
            assert.equal(attrs.viewboxX, newAttrs.x);
            assert.equal(attrs.viewboxY, newAttrs.y);
            assert.equal(attrs.viewboxWidth, newAttrs.width);
            assert.equal(attrs.viewboxHeight, newAttrs.height);
        });
    });
});
