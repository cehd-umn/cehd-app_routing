const $ = require("jquery");
const assert = require("assert");
const baseClass = require("../base.js");
const CEHDSVG = require("../svg/svg.js");
const CEHDCircle = require("../svg/circle.js");
const CEHDViewbox = require("../svg/viewbox.js");

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

describe("Viewbox", function() {
    const viewboxAttrs = {
        "viewboxX": "x",
        "viewboxY": "y",
        "viewboxWidth": "width",
        "viewboxHeight": "height"
    };
    const viewboxInstance = new CEHDViewbox(viewboxAttrs);

    describe("getAttrsFromData", function() {
        // This is what the properties will look like after they're retrieved with $.data()
        const data = {
            "viewboxX": "x",
            "viewboxY": "y",
            "viewboxWidth": "width",
            "viewboxHeight": "height",
            "foo": "bar"
        };

        const viewboxAttrs = viewboxInstance.getAttrsFromData(data);

        it("should return an object with the properties required to create a viewbox", function() {
            assert.equal(data.viewboxX, viewboxAttrs.x);
            assert.equal(data.viewboxY, viewboxAttrs.y);
            assert.equal(data.viewboxHeight, viewboxAttrs.height);
            assert.equal(data.viewboxWidth, viewboxAttrs.width);
        });

        it("should return an object with only the numbers of properties that matches our expectations", function() {
            const expectedNumber = 4;
            assert.equal(expectedNumber, Object.keys(viewboxAttrs).length); // foo doesn't get sent back
        });
    });
});

describe("Circle", function() {
    const circleInstance = new CEHDCircle({
            "circleBgColor": "fill",
            "circleCenterX": "cx",
            "circleCenterY": "cy"
        });

    describe("getCircleAttrsFromData", function() {
        // This is what the properties will look like after they're retrieved with $.data()
        const data = {
            "circleBgColor": "string",
            "circleCenterX": "string or number",
            "circleCenterY": "string or number",
            "foo": "bar"
        };
        const circleAttrs = circleInstance.getAttrs(data);

        it("should return an object with the properties required to create a circle in an SVG", function() {
            assert.equal(data.circleBgColor, circleAttrs.fill);
            assert.equal(data.circleCenterX, circleAttrs.cx);
            assert.equal(data.circleCenterY, circleAttrs.cy);
        });

        it("should return an object with the properties named like properties of an svg.js object's properties", function() {
            assert.equal(true, "fill" in circleAttrs);
            assert.equal(true, "cx" in circleAttrs);
            assert.equal(true, "cy" in circleAttrs);
            assert.equal(false, "foo" in circleAttrs); // foo doesn't get sent back
        });

        it("should return an object with only three properties in the above test", function() {
            assert.equal(3, Object.keys(circleAttrs).length); // foo doesn't get sent back
        });
    });
});
