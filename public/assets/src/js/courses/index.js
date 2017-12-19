const http_client = require("../http_client.js");
const $ = require("jquery");
const renderer = require("../renderer.js");

const courses_containers = $("[data-courses-app]");

if (courses_containers.length > 0) {
    courses_containers.each(function(k, v) {
        const httpClient = new http_client();
        const base_url = "/services/programs/v2/";
        const container = $(v);
        const target_element_selector = container.data("target-element-selector");
        const template_name =           container.data("template-name");
        const template_path =           container.data("template-path");
        const limit =                   container.data("limit");
        const a =                       container.data("a");
        const d =                       container.data("d");
        const h =                       container.data("h");
        const p =                       container.data("p");
        const t =                       container.data("t");
        const request_params = {
            url: base_url,
            data: {
                a: a,
                d: d,
                h: h,
                p: p,
                t: t
            }
        };
        const request = httpClient.get(request_params);

        request.done(function() {
            const results = httpClient.getResults();
            const template_params = {
                "template_path": template_path,
                "template_name": template_name,
                "$target_element": $(target_element_selector),
                "data": {
                    programs: results,
                    limit: limit
                }
            };
            const view = new renderer();
            view.init(template_params);
            view.render();
        });
    });
}
