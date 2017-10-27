const filterManager = require("./filterManager.js");

const fm = new filterManager("#toc-container", ".category-select");

fm.setUIState(window.location.search, '.toc-select');

window.onpopstate = function() {
    fm.setUIState(window.location.search, '.toc-select');
}
