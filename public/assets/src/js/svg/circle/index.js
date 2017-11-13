const svg = require("svg.js");
const $ = require("jquery");

const svg_containers = $("[data-svg-stat-group]");

if (svg_containers.length > 0) {
    svg_containers.each(function() {
        const container = $(this);

        const circle_attributes = {
            fill: container.data("circle-bg-color"),
            cx: container.data("circle-center-x"),
            cy: container.data("circle-center-y")
        };

        const line_attributes = {
            color: container.data("line-stroke-color"),
            opacity: container.data("line-stroke-opacity"),
            width: container.data("line-stroke-width")
        };

        const line_start_x = container.data("line-start-x");
        const line_start_y = container.data("line-start-y");
        const line_end_x = container.data("line-end-x");
        const line_end_y = container.data("line-end-y");

        const stat_text_attributes = {
            x: container.data("stat-text-x"),
            y: container.data("stat-text-y"),
            fill: container.data("stat-text-color"),
        };

        const stat_text_font_attributes = {
            family: container.data("stat-text-font-family"),
            size: container.data("stat-text-font-size"),
        };

        const text_text_attributes = {
            x: container.data("text-x"),
            y: container.data("text-y"),
            fill: container.data("text-color"),
        };

        const text_text_font_attributes = {
            family: container.data("text-font-family"),
            size: container.data("text-font-size"),
        };

        const caption_text_attributes = {
            x: container.data("caption-text-x"),
            y: container.data("caption-text-y"),
            fill: container.data("caption-text-color"),
        };

        const caption_text_font_attributes = {
            family: container.data("caption-text-font-family"),
            size: container.data("caption-text-font-size"),
        };

        const text_lines = container.data("text-lines");

        const draw = svg(container.attr("id"));

        draw.viewbox(
            container.data("viewbox-x"),
            container.data("viewbox-y"),
            container.data("viewbox-width"),
            container.data("viewbox-height"));

        const group = draw.group();
        const circle = draw.circle(container.data("circle-size")).attr(circle_attributes);
        const stat_text = draw.text(container.data("stat-text-value"))
            .attr(stat_text_attributes)
            .font(stat_text_font_attributes);

        const caption_text = draw.text(container.data("caption-text-value"))
            .attr(caption_text_attributes)
            .font(caption_text_font_attributes);

        const text_text = draw.text(function(add) {
            $.each(text_lines, function() {
                const txt = this;
                add.tspan(txt).newLine();
            });
        })
        .attr(text_text_attributes)
        .font(text_text_font_attributes);

        const line = draw.line(
            line_start_x,
            line_start_y,
            line_end_x,
            line_end_y
        ).stroke(line_attributes);

        group.add(circle);
        group.add(stat_text);
        group.add(text_text);
        group.add(caption_text);
        group.add(line);
    });
}
