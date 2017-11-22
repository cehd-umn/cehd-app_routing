# CEHD Routes, Templates, SASS & JavaScript


This is an "app" in a very strict sense, but we've tried to keep the back end
logic to a minimum to keep this app very small and fast.

The app uses [Twig for templates](http://twig.sensiolabs.org/) and [the Slim
framework for routing](https://www.slimframework.com/). What does that
mean? Let's talk requests.

[Go here for more detailed documentation](/docs/).

## Requests

Let's imagine a web server hosting static HTML pages. Those are just plain text
files as I'm sure you know. A browser sends a request to the server. If the
server can find a file that matches the request it returns a status code of
`200 OK` and sends the contents of the file back. (If the server can't find it,
it sends a `404` status code back.)

We're abstracting that transaction a little. The server is configured to send
all requests for sites that use this application to `public/<website>/index.php`.
Thus requests for the Ed Psych site go to `public/edpsych/index.php`.

That file is written to route requests to their proper Twig template or return
a 404 if there is not a matching route. Slim handles routing requests to the
right place and sending a response back. Twig is the template engine.

## Templates

Where is the content? Rather than a content management system that would
require a database, we're keeping the content in some templates. This is fast
and secure.  It also allows us to take advantage of Twig's features and put a
little logic in the back end. More on that later.

### Finding The Right Template

Templates are in the `templates` directory. The template directory structure
will closely match the structure of the site. The template for any main section
of a site will be named `index.html`. E.g, the page for edpsych/about/ will be
at `templates/edpsych/about/index.html`.

### Editing Templates

[Twig for Template Designers](http://twig.sensiolabs.org/doc/templates.html)
has a lot of good information. Here are the basics.

Templates can "extend" other templates. That allows us to reuse code across
pages and entire sites. E.g., the University header and footer. That code can
exist in a single template and other templates can *extend* it and inject
code into "blocks."

#### Blocks

We start with a [`base.html`](templates/layout/base.html) template that
contains all the code that will be common from site to site. That template
has several blocks defined including `title` and `main_content`. If you
include a block in your template called "title", it will populate the
title element from the base template.

```
{# title element from base.html #}
<title>{% block title %}{% endblock %}</title>
```

```
{# title block in another template; will populate page title element #}
{% block title %}
    Title of my page
{% endblock %}
```

Two things to note: blocks are defined by `{%` the block name `%}` and a closing
`{% endblock %}` statement. Content within the block is inserted into
the parent template where that same block is defined.

```
    base.html
    |-- title block
    |
    |----index.html
    |------ title block (content here gets inserted into the title block in base.html above)
```

[See the templates directory for more details on specific templates](templates).

#### Variables

It's also possible to define variables for reuse within a template. For example,
you may want the `<h1>` to have the same content as the page title element.

Variables are set using the `set` keyword enclosed in `{% %}`.

```
{% set my_variable = "My variable value" %}
```

Once variables are set, they can be inserted anywhere in the template by enclosing
the variable name inside double curly braces.

```
{{ my_variable }}{# will render "My variable value" #}
```

To use the same value for the title element as the `<h1>` tag you would do the
following:

```
{# set the variable #}
{% set page_title = "About the Department of Educational Psychology" %}

{# use it to set both the page title and h1 #}
{% block title %}{{ page_title }}{% endblock %}
{% block heading_1 %}{{ page_title }}{% endblock %}
```

Variables are good for most cases when you find you're repeating something on a
page or across pages.
