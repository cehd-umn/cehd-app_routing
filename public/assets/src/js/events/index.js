const http_client = require('../http_client.js');
const $ = require('jquery');
const renderer = require('../renderer.js');

const events_containers = $('[data-events-app]');

if (events_containers.length > 0) {
	events_containers.each(function(k, v) {
		const httpClient = new http_client();
		const base_url = '/services/events/feed/';
		const container = $(v);
		const target_element_selector = container.data('target-element-selector');
		const template_name = container.data('template-name');
		const template_path = container.data('template-path');
		const limit = container.data('limit');
		const q = container.data('q');
		const r = container.data('r');
		const t = container.data('t');
		const z = container.data('t');
		const request_params = {
			url: base_url,
			data: {
				q: q,
				r: r,
				t: t,
				z: z,
				x: limit
			}
		};
		const request = httpClient.get(request_params);

		request.done(function() {
			const results = httpClient.getResults();
			// console.log(results);

			const template_params = {
				template_path: template_path,
				template_name: template_name,
				$target_element: $(target_element_selector),
				data: {
					events: results,
					limit: limit
				}
			};
			console.log(template_params);
			const view = new renderer();
			view.init(template_params);
			view.render();
		});
	});
}
