class Helpers {
    static feedPathToLinkPath(feed_path) {
        return feed_path.replace("feed/", "");
    }

    static getHref($element) {
        const href = (typeof($element.attr("href")) !== "undefined") ? $element.attr("href") : "";
        return href;
    }
}

module.exports = Helpers;
