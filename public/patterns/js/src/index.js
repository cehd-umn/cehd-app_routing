const filterManager = require("./filterManager.js");

const fm = new filterManager("#toc-container", ".category-select", ".active-filter-list");

fm.setUIState(window.location.search, '.toc-select');

window.onpopstate = function() {
    fm.setUIState(window.location.search, '.toc-select');
}
