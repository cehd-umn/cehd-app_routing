const http_client = require("../http_client.js");
const $ = require("jquery");
const renderer = require("../renderer.js");

const news_containers = $("[data-news-app]");

if (news_containers.length > 0) {
    news_containers.each(function(k, v) {
        const httpClient = new http_client();
        const base_url = "/services/news/feed/";
        const container = $(v);
        const feed_base_url =           container.data("base-url");
        const feed_path =               container.data("feed-path");
        const target_element_selector = container.data("target-element-selector");
        const template_name =           container.data("template-name");
        const template_path =           container.data("template-path");
        const limit =                   container.data("limit");
        const news_link =               container.data("news-link");
        const news_text =               container.data("news-text");
        const request_params = {
            url: base_url,
            data: {
                "feed-path": feed_path,
                "feed_url": feed_base_url
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
                    news: results,
                    more_news_link: news_link,
                    more_news_text: news_text,
                    limit: limit
                }
            };
            const view = new renderer();
            view.init(template_params);
            view.render();
        });
    });
}
