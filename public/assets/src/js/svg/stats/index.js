const svg = require("svg.js");
const $ = require("jquery");
const CEHDCircle = require("../circle.js");
const CEHDViewbox = require("../viewbox.js");
const svg_containers = $("[data-svg-stat-group]");
const circleKeyMap = {
            "circleBgColor": "fill",
            "circleCenterX": "cx",
            "circleCenterY": "cy",
            "circleSize": "size"
        };
const cehdCircle = new CEHDCircle(circleKeyMap);
const viewboxKeyMap = {
        "viewboxX": "x",
        "viewboxY": "y",
        "viewboxWidth": "width",
        "viewboxHeight": "height"
    };
const cehdViewbox = new CEHDViewbox(viewboxKeyMap);

if (svg_containers.length > 0) {
    svg_containers.each(function() {
        let circle;
        const container = $(this);
        const container_data = container.data();
        const draw = svg(container.attr("id"));

        if (cehdViewbox.validateAttrs(container_data)) {
            const attrs = cehdViewbox.getAttrs(container_data);
            draw.viewbox(attrs.x, attrs.y, attrs.width, attrs.height);
        }

        const group = draw.group();

        if (cehdCircle.validateAttrs(container_data)) {
            const circle_attributes = cehdCircle.getAttrs(container_data);
            circle = draw.circle(circle_attributes.size).attr(circle_attributes);
        }

        const line_attributes = {
            color: container.data("line-stroke-color"),
            opacity: container.data("line-stroke-opacity"),
            width: container.data("line-stroke-width")
        };

        const line = draw.line(
            line_start_x,
            line_start_y,
            line_end_x,
            line_end_y
        ).stroke(line_attributes);

        const line_start_x = container.data("line-start-x");
        const line_start_y = container.data("line-start-y");
        const line_end_x = container.data("line-end-x");
        const line_end_y = container.data("line-end-y");

        // stat text
        const stat_text_attributes = {
            x: container.data("stat-text-x"),
            y: container.data("stat-text-y"),
            fill: container.data("stat-text-color"),
        };

        const stat_text_font_attributes = {
            family: container.data("stat-text-font-family"),
            size: container.data("stat-text-font-size"),
        };

        const stat_text = draw.text(container.data("stat-text-value"))
            .attr(stat_text_attributes)
            .font(stat_text_font_attributes);

        // main text
        const main_text_attributes = {
            x: container.data("text-x"),
            y: container.data("text-y"),
            fill: container.data("text-color"),
        };

        const main_text_font_attributes = {
            family: container.data("text-font-family"),
            size: container.data("text-font-size"),
        };

        const text_lines = container.data("text-lines");

        const main_text = draw.text(function(add) {
            $.each(text_lines, function() {
                const txt = this;
                add.tspan(txt).newLine();
            });
        })
        .attr(main_text_attributes)
        .font(main_text_font_attributes);

        // caption_text
        const caption_text_attributes = {
            x: container.data("caption-text-x"),
            y: container.data("caption-text-y"),
            fill: container.data("caption-text-color"),
        };

        const caption_text_font_attributes = {
            family: container.data("caption-text-font-family"),
            size: container.data("caption-text-font-size"),
        };

        const caption_text = draw.text(container.data("caption-text-value"))
            .attr(caption_text_attributes)
            .font(caption_text_font_attributes);

        // Add stuff to the group
        group.add(circle);
        group.add(stat_text);
        group.add(main_text);
        group.add(caption_text);
        group.add(line);
    });
}
