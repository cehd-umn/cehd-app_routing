class Helpers {
    constructor() {
        return this;
    }

    feedPathToLinkPath(feed_path) {
        return feed_path.replace("feed/", "");
    }

    getHref($element) {
        href = (typeof($element.attr("href")) !== "undefined") ? $element.attr("href") : "";
        return href;
    }
}

module.exports = Helpers;
