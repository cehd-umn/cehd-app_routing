const ppl = require("./people.js");
const $ = require("jquery");
const groups = require("../group.js");
const renderer = require("../renderer.js");

const people_app_containers = $("[data-people-app]");

if (people_app_containers.length > 0) {
    const people = new ppl();
    people_app_containers.each(function(k, v) {
        const container = $(v);
        const tg = container.data("tag");
        const target_element_selector = container.data("target-element-selector");
        const template_name = container.data("template-name");
        const template_path = container.data("template-path");
        const limit = container.data("people-per-group");
        const results = people.getPeople("/services/people-tool/", tg);

        results.request.done(function() {
            const people = results.client.getResults();
            const grps = new groups(people, limit);
            const view = new renderer();
            const template_params = {
                "template_path": template_path,
                "template_name": template_name,
                "$target_element": $(target_element_selector),
                "data": {
                    "people": grps
                }
            };
            view.init(template_params);
            view.render();
        });
    });
}
