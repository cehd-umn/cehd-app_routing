const ppl = require("./people.js");
const $ = require("jquery");
const groups = require("../group.js");
const renderer = require("../renderer.js");

const people_containers = $("[data-people-app]");
const person_container = $("[data-person-app]");
const people_with_extra_containers = $("[data-people-with-extra-app]");
const person_with_extra_containers = $("[data-person-with-extra-app]");

if (person_with_extra_containers.length > 0) {
    person_with_extra_containers.each(function(k, v) {
        const container = $(v);
        const people = new ppl();
        const internet_id = container.data("internet-id");
        const target_element_selector = container.data("target-element-selector");
        const template_name = container.data("template-name");
        const template_path = container.data("template-path");
        const extra_data_url = container.data("extra-data-url");
        const extra_data_data = container.data("extra-data-data");
        const results = people.getPersonWithExtraData("/services/people-tool/person/", internet_id, extra_data_url, extra_data_data);

        results.person.request.done(function() {
            results.extraData.request.done(function(extra_data) {
                const person = results.person.client.getResults();
                const view = new renderer();
                person.extraData = extra_data;
                console.log(person);
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
        });
    });
}

if (people_with_extra_containers.length > 0) {
    const people = new ppl();
    people_with_extra_containers.each(function(k, v) {
        const container = $(v);
        const tg = container.data("tag");
        const target_element_selector = container.data("target-element-selector");
        const template_name = container.data("template-name");
        const template_path = container.data("template-path");
        const extra_data_url = container.data("extra-data-url");
        const extra_data_data = container.data("extra-data-data");
        const limit = container.data("people-per-group");
        const results = people.getPeopleWithExtraData("/services/people-tool/", tg, extra_data_url, extra_data_data);

        // @TODO these results callbacks are not DRY
        results.people.request.done(function() {
            results.extraData.request.done(function(extra_data) {
                const people = results.people.client.getResults();
                const view = new renderer();

                people.each(function(k, person) {
                    people[person.internetId].extraData = extra_data[person.internetId];
                });

                const grps = new groups(people, limit);
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
    });
}

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
