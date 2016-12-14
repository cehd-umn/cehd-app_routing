# Ed Psych Templates

This is the directory that contains all the templates related to the Ed Psych
site specifically.

## Navigation

To make updating and coding the site navigation simpler, the information about
the main navigation is stored in [a YAML
file](../cehd/config/nav/edpsych/nav.yml).

The information in that file is parsed into an array. [The Ed Psych `base.html`
template](layout/base.html) loops over the array to output the navigation.  
Here is a snipped of the YAML file that defines the main nav:

```
---
- name: Home
  href: /
  section: home
- name: Programs & Degrees
  href: /programs/
  section: programs-degrees
  subnav:
    - name: American Sign Language
      href: /programs/asl/
    - name: Counseling & Student Personnel Psychology
      href: /programs/cspp/
    - name: Psychological Foundations of Education
      href: /programs/foundations/
    - name: Quantitative Methods in Education
      href: /programs/qme/
    - name: School Psychology
      href: /programs/schoolpsych/
    - name: Special Education
      href: /programs/specialed/
    - name: Graduate Minor
      href: /programs/graduate-minor/
    - name: Undergraduate Minor
      href: /programs/minor/
```

YAML is *very particular* about indentation. The file must has two-space
indentation. (No tabs.)

If you wanted to add a new section, you could add this:

```
- name: New Section
  href: /new-section/
  section: new-section
```

These are just key/value pairs. `name` will be the title of the nav link, `href`
will populate the `href` attribute of the link and `section` is used to identify
the current section so the template knows where to add the `.current` CSS class.

If the new section requires sub-navigation:

```
- name: New Section
  href: /new-section/
  section: new-section
  subnav:
    - name: New Sub Section
      href: /new-section/new-subsection/
    - name: Yet Another New Sub Section
      href: /new-section/yanss/
```

Currently only two levels of navigation are supported.

These values are injected into the `config.nav_items` variable that all
templates get. Twig supports `for` loops so we just do this in [the Ed Psych
`base.html` template](layout/base.html):

```
{% for nav_item in config.nav_items %}
    // render html w/ nav_item data
{% endfor %}
```

## Global Variables

A handful of global variables are available for convenience.

### `config.asset_base`

The path to Ed Psych's assets directory.

### `config.base_url`

The base url for the Ed Psych site. We put this here because the url will
change once the site goes live. Changing this url will be as simple as changing
this variable to update all pages.

### `config.copyright_year`

This is a dynamically generated four-digit year so copyright dates will update
themselves.

### `config.css_base`

The path to Ed Psych's CSS directory.

### `config.dept_name`

The official way the department name should be spelled out. This is so it's
easier to display the name consistently.

### `config.js_base`

The path the Ed Psych's JavaScript directory.

### `config.nunjucks_view_base`

The path to Ed Psych's [nunjucks.js](https://mozilla.github.io/nunjucks/)
template directory. Nunjucks is used to render People Tool, News and Events
feeds. It's the equivalent of Twig for the JavaScript world.

### `config.nav_items`

A nested array of navigation items.

### `config.site_config`

An array of site config options. For example, `config.site_config.debug` is a
boolean value indicating whether not the site is in debug mode.

`config.site_config.environment` contains a string of either "production" for
when the site is live or "development" for when it's on drydock.
