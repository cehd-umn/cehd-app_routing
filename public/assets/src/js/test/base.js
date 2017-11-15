const assert = require("assert");
const baseClass = require("../base.js");

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
    });
});
