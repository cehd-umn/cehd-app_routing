# Ed Psych Templates

This is the directory that contains all the templates related to the Ed Psych
site specifically.

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
