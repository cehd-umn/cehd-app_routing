const nunjucks = require("nunjucks");
const $ = require("jquery");

class Renderer {

    constructor() {
        this.defaults = {
            template_path: "../views",
            $target_element: $("#results-container")
        };

        this.settings = null;

        this.env = null;
    }


    // @todo This initializiation can go in the constructor
    init(params) {
        this.settings = this._mergeOptions(params);
        this.env = nunjucks.configure(this.settings.template_path, { autoescape: true });
        this.addFilters();
    }

    addFilters() {
        // Custom nunjucks filter
        // http://stackoverflow.com/questions/29844865/nunjucks-check-for-object-or-string
        this.env.addFilter("is_string", function(obj) {
            return typeof obj === "string";
        });

        this.env.addFilter("strip_style_attributes", function(str) {
            return str.replace(/style=(".*?"|'.*?'|[^"'][^\s]*)/g, "");
        });
    }

    render(append) {
        const html = this.env.render(this.settings.template_name,
            {
                data: this.settings.data
            }
        );

        let function_name = "html";

        if (append) {
            function_name = "append";
        }

        this.settings.$target_element[function_name](html);
    }

    _mergeOptions(params) {
        return $.extend(this.defaults, params);
    }
}

module.exports = Renderer;
