# Templates

Our templates use [the Twig template engine](http://twig.sensiolabs.org/). Twig
uses a pattern found in many programming languages. Django and Jinja templates
have an almost identical syntax for Python applications. Nunjucks is the
templating engine for JavaScript that uses that syntax.

One of the advantages we get from using a template engine like this is template
inheritance. Templates can "extend" other templates. That allows us to organize
our code so that we can make changes in one place and all child templates are
instantly updated. Parent templates can be shared across projects and sites.

It also allows us to get a lot of code out of the way so that it's easier to
edit content without all the clutter of code that is common to all pages.
Things like making sure every page has the correct document type declaration,
or the right style sheets are abstracted away and managed in one place.

## Template Structure

Our templates follow a common pattern of starting with a ["base"
template](templates/layout/base.html) and extending other templates from there.

The base template is meant to contain all the code that is common to *every
page*. For example, every page should start with `<!doctype html>` on the
first line. Therefore that goes in the base template.

Department templates will have their own common code so departments get their
own `base.html`. I.e., [Ed Psych's
`base.html`](templates/edpsych/layout/base.html). Templates can have
multiple levels of inheritance, so Ed Psych's `base.html` extends
the top-level `base.html`.

```
{% extends "layout/base.html" %}
```

We can keep building out templates based on their role until we get down
to a level where there is no longer common code between pages. E.g.,
for Ed Psych we created [`section.html` and `home.html`](templates/edpsych/layout)
with the thought that the home page may look significantly different
than the rest of the site. Thus we end up with a structure that looks like this:

* layout/base.html
  * edpsych/layout/
    * base.html (extends layout/base.html)
      * section.html (extends edpsych/layout/base.html)
      * home.html (extends edpsych/layout/base.html)

[`edpsych/layout/base.html`](templates/edpsych/layout/base.html) contains
elements that *are only common to the Ed Psych site* and not common
to other sites. Things like the Ed Psych wordmark and navigation.

Now that we've organized our templates with common code, we can build off that
to build relatively simple templates that will contain the content. At this
point the structure of the [templates directory](templates/edpsych/) will
roughly match the information architecture of the site. So the template for
`/edpsych/about/` is at
[`templates/edpsych/about/index.html`](templates/edpsych/about/index.html).

At that level most of the HTML has been abstracted away and content strategists
can focus on the content of the page for the most part. They can write
their content as HTML.

### TODO

[ ] Write documentation for how the navigation works

### Blocks

Child templates can inject content into `base.html`. In order to do that
`base.html` must contain a matching block.

For example, if this code were at the top of `base.html`:

```
<!doctype html>
<html>
    <head>
        <title>{% block title %}{% endblock %}</title>
    </head>
    <body>
    ...
    </body>
</html>
```

Any other template that extends `base.html` can inject a value into the page
title like this:

```
{# Tell Twig which template to extend %}
{% extends "layout/base.html" %}

{# Populate the title block #}
{% block title %}This is My Page Title{% endblock %}
```

### Available Blocks

Here is a list of blocks that are defined and what their purpose is.

#### `title`

As shown in the example above, this is used to inject a page title from a child
template.

#### `title_prepend` and `title_append`

These are meant to inject text before or after whatever is in the `title`
block.

The purpose of this is in case we want the name of the department or
"University of Minnesota" to get prepended or appended across several pages in
a consistent way.

#### `inline_css`

This is intended for department-level inline CSS to speed up page rendering.
See [Google
Pagespeed](https://developers.google.com/speed/docs/insights/OptimizeCSSDelivery).

#### `css`

This is the main department-level block for linking to style sheets.

#### `extra_css`

This is a block for content strategists to inject their own special styles.

#### `heading_1`

This block populates the `<h1>` tag, which is hidden visually and placed
at the top of the document.

#### `section_wordmark`

This block places a department wordmark alongside the CEHD wordmark.

#### `nav`

You guessed it. The nav goes in this block.

#### `main_content`

Page content goes here.

### Global Variables

To avoid repetition and also for convenience, a handful of global variables are
defined for all templates. For an example, see [the Ed Psych template
Readme](edpsych/Readme.md).
