const svg = require("svg.js");
const $ = require("jquery");
const CEHDSVG = require("../svg.js");
const svg_containers = $("[data-svg-stat-group]");
const circleKeyMap = {
            "circleBgColor": "fill",
            "circleCenterX": "cx",
            "circleCenterY": "cy",
            "circleSize": "size"
        };
const cehdCircle = new CEHDSVG(circleKeyMap);
const viewboxKeyMap = {
        "viewboxX": "x",
        "viewboxY": "y",
        "viewboxWidth": "width",
        "viewboxHeight": "height"
    };
const cehdViewbox = new CEHDSVG(viewboxKeyMap);
const lineKeyMap = {
        "lineStartX": "x1",
        "lineStartY": "y1",
        "lineEndX": "x2",
        "lineEndY": "y2"
    };
const strokeKeyMap = {
        "lineStrokeColor": "color",
        "lineStrokeOpacity": "opacity",
        "lineStrokeWidth": "width"
    };
const cehdLine = new CEHDSVG(lineKeyMap);
const cehdLineStroke = new CEHDSVG(strokeKeyMap);
const statTextKeyMap = {
        "statTextX": "x",
        "statTextY": "y",
        "statTextColor": "fill"
    };
const statTextFontKeyMap = {
        "statTextFontFamily": "family",
        "statTextFontSize": "size",
    };
const cehdStatText = new CEHDSVG(statTextKeyMap);
const cehdStatTextFont = new CEHDSVG(statTextFontKeyMap);

if (svg_containers.length > 0) {
    svg_containers.each(function() {
        let circle;
        const container = $(this);
        const containerData = container.data();
        const draw = svg(container.attr("id"));

        if (cehdViewbox.validateAttrs(containerData)) {
            const attrs = cehdViewbox.getAttrs(containerData);
            draw.viewbox(attrs.x, attrs.y, attrs.width, attrs.height);
        }

        const group = draw.group();

        if (cehdCircle.validateAttrs(containerData)) {
            const circleAttributes = cehdCircle.getAttrs(containerData);
            circle = draw.circle(circleAttributes.size).attr(circleAttributes);
        }

        // @todo write a line class to handle these two things separately and validate them together
        if (cehdLine.validateAttrs(containerData) && cehdLineStroke.validateAttrs(containerData)) {

            const lineAttrs = cehdLine.getAttrs(containerData);
            const strokeAttrs = cehdLineStroke.getAttrs(containerData);
            const line = draw.line(
                lineAttrs.x1,
                lineAttrs.y1,
                lineAttrs.x2,
                lineAttrs.y2
            ).stroke(strokeAttrs);
        };

        // stat text
        if (cehdStatText.validateAttrs(containerData) && cehdStatTextFont.validateAttrs(containerData)) {
            const textAttrs = cehdStatText.getAttrs(containerData);
            const fontAttrs = cehdStatTextFont.getAttrs(containerData);
            const stat_text = draw.text(container.data("stat-text-value"))
                .attr(textAttrs)
                .font(fontAttrs);
        }

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
