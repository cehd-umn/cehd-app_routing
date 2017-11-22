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
const viewboxKeyMap = {
        "viewboxX": "x",
        "viewboxY": "y",
        "viewboxWidth": "width",
        "viewboxHeight": "height"
    };
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
const statTextKeyMap = {
        "statTextX": "x",
        "statTextY": "y",
        "statTextColor": "fill"
    };
const statTextFontKeyMap = {
        "statTextFontFamily": "family",
        "statTextFontSize": "size",
    };
const mainTextKeyMap = {
        "mainTextX": "x",
        "mainTextY": "y",
        "mainTextColor": "fill"
    };
const mainTextFontKeyMap = {
        "mainTextFontFamily": "family",
        "mainTextFontSize": "size",
    };
const captionTextKeyMap = {
        "captionTextX": "x",
        "captionTextY": "y",
        "captionTextColor": "fill"
    };
const captionTextFontKeyMap = {
        "captionTextFontFamily": "family",
        "captionTextFontSize": "size",
    };
const cehdCircle = new CEHDSVG(circleKeyMap);
const cehdViewbox = new CEHDSVG(viewboxKeyMap);
const cehdLine = new CEHDSVG(lineKeyMap);
const cehdLineStroke = new CEHDSVG(strokeKeyMap);
const cehdStatText = new CEHDSVG(statTextKeyMap);
const cehdStatTextFont = new CEHDSVG(statTextFontKeyMap);
const cehdMainText = new CEHDSVG(mainTextKeyMap);
const cehdMainTextFont = new CEHDSVG(mainTextFontKeyMap);
const cehdCaptionText = new CEHDSVG(captionTextKeyMap);
const cehdCaptionTextFont = new CEHDSVG(captionTextFontKeyMap);

if (svg_containers.length > 0) {
    svg_containers.each(function() {
        let circle;
        let line;
        let statText;
        let mainText;
        let captionText;
        const container = $(this);
        const containerData = container.data();
        const draw = svg(container.attr("id"));
        const group = draw.group();

        if (cehdViewbox.validateAttrs(containerData)) {
            const attrs = cehdViewbox.getAttrs(containerData);
            draw.viewbox(attrs.x, attrs.y, attrs.width, attrs.height);
        }

        if (cehdCircle.validateAttrs(containerData)) {
            const attrs = cehdCircle.getAttrs(containerData);
            circle = draw.circle(attrs.size).attr(attrs);
        }

        // @todo write a line class to handle these two things separately and validate them together
        if (cehdLine.validateAttrs(containerData) && cehdLineStroke.validateAttrs(containerData)) {

            const lineAttrs = cehdLine.getAttrs(containerData);
            const strokeAttrs = cehdLineStroke.getAttrs(containerData);
            line = draw.line(
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
            statText = draw.text(containerData["statTextValue"])
                .attr(textAttrs)
                .font(fontAttrs);
        }

        // main text
        if (cehdMainText.validateAttrs(containerData) && cehdMainTextFont.validateAttrs(containerData)) {
            const textAttrs = cehdMainText.getAttrs(containerData);
            const fontAttrs = cehdMainTextFont.getAttrs(containerData);
            const textLines = containerData["mainTextLines"];
            mainText = draw.text(function(add) {
                textLines.forEach(function(txt) {
                    add.tspan(txt).newLine();
                });
            })
            .attr(textAttrs)
            .font(fontAttrs);
        }

        // caption_text
        if (cehdCaptionText.validateAttrs(containerData) && cehdCaptionTextFont.validateAttrs(containerData)) {
            const textAttrs = cehdCaptionText.getAttrs(containerData);
            const fontAttrs = cehdCaptionTextFont.getAttrs(containerData);
            const textLines = containerData["captionTextLines"];
            captionText = draw.text(container.data("caption-text-value"))
                .attr(textAttrs)
                .font(fontAttrs);
        }

        // Add stuff to the group
        group.add(circle);
        group.add(statText);
        group.add(mainText);
        group.add(captionText);
        group.add(line);
    });
}
