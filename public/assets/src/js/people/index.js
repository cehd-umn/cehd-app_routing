const ppl = require("./people.js");
const $ = require("jquery");
const groups = require("../group.js");
const renderer = require("../renderer.js");

const people_containers = $("[data-people-app]");
const person_container = $("[data-people-app]");

if (people_containers.length > 0) {
    const people = new ppl();
    people_containers.each(function(k, v) {
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

if (person_container.length > 0) {
    const people = new ppl();
    const internet_id = person_container.data("internet-id");
    const target_element_selector = person_container.data("target-element-selector");
    const template_name = person_container.data("template-name");
    const template_path = person_container.data("template-path");
    const results = people.getPerson("/services/people-tool/person/", internet_id);

    results.request.done(function() {
        const person = results.client.getResults();
        const view = new renderer();
        const template_params = {
            "template_path": template_path,
            "template_name": template_name,
            "$target_element": $(target_element_selector),
            "data": {
                "person": person
            }
        };
        view.init(template_params);
        view.render();
    });
}
