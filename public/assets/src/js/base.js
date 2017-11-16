class Base {
    bothAreArrays(arr1, arr2) {
        return arr1 instanceof Array && arr2 instanceof Array;
    }

    arrayContainsExpected(expected, actual) {
        for (var i = 0; i < expected.length; i++) {
            if (this.bothAreArrays(expected[i], actual[i])) {
                if (!this.arrayContainsExpected(expected[i], actual[i])) {
                    return false;
                }
            } else if (!actual.includes(expected[i])) {
                return false;
            }
        }

        return true;
    }

    containsExpectedKeys(expected, actual) {
        for (let key in expected) {
            if (this.bothAreArrays(expected[key], actual[key])) {
                if (!this.containsExpectedKeys(expected[key], actual[key])) {
                    return false;
                }
            } else if (typeof actual[key] == "undefined") {
                return false;
            }
        }

        return true;
    }
}

module.exports = Base;
