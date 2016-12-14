# The Ed Psych App

This is only an app in the strictest sense. There's actually very little
business logic here. The `index.php` file is *mostly* a way of pointing urls
(or "routes") to the correct Twig template. These routes are handled using
[Klein](https://github.com/klein/klein.php), a very lightweight routing
framework written in PHP.

Probably the most common change here will be setting up a new route and
pointing it to a new template.

## Defining a New Route

Everything before this statement is configuration, which is covered below:

```
$router->with($global_config['base_url'], function() use ($router) {
    // all routes found here...
});
```

All sub-routes are configured inside those curly braces. If you look a bit
further down you'll see this:

```
$router->respond('GET', '/', function($request, $response, $service, $app) {
    echo $app->template->render('edpsych/index.html', []);
});
```

Let's break that down. The first thing we see inside `$router->respond()` is
`'GET'`. `GET` is the request method. Here we're saying all the following code
will handle `GET` requests to this route (as opposed to `POST` requests, for
example).

What route? That's the next parameter: `$router->respond('GET', '/', ...);`.

In this case the route is `'/'`, which is the Ed Psych home page. So this
is the logic for the home page.

Next we see an anonymous function.

```
$router->respond('GET', '/', function($request, $response, $service, $app) {...
```

That's the *callback*. Klein magically passes in four parameters. They are
all PHP objects. Their names are pretty intuitive:

* The `$request` object contains some handy info about the incoming HTTP request.
* `$response` is a Klein Response object for handing our response to the request.
* `$service` is a Klein Service object for any defined services, like a
  database instance. (This is unused right now.)
* `$app` is a Klein App object which we've configured to do a few things for us.
  (More on that below.)

Don't worry too much about these for the moment.

Now we get to the fun part. Let's put is all back together:

```
$router->respond('GET', '/', function($request, $response, $service, $app) {
    echo $app->template->render('edpsych/index.html', []);
});
```

This is where the magic happens:
`echo $app->template->render('edpsych/index.html', []);`

`$app->template` is our Twig instance. So we're saying, render the template you
find at this path (`'edpsych/index.html'`) and pass in this array of variables
for the template to use. (In this case, we're not passing any variables in, so
the array is empty (`[]`).)

As long as the path to the template is correct (and the template exists), you
have a new route.

## Route Namespacing

If you look through all the routes, you'll see they're organized into site
sections using a `with` method.

```
$router->with('/about', function() use ($router) {...});
```

That creates a "namespace" within Klein. Namespaces are similar to directories
on a computer. I can't have two files called "about.txt" in the same directory,
but I can have to files called "about.txt" in different directories. The
same concept applies here. This organizes routes within section namespaces,
keeps the code a little more concise and makes the routes more readable.

Whenever you see "with", it represents a different section to the site starting
with `/`. All routes within that section are nested inside the `with` method.
That's about all you need to worry about with namespacing.

## Configuration

Site configuration is generated from YAML files. Multiple files can be parsed
by the Config class to return a single array of values. This makes adding new
configuration values very simple. There are app-level configuration values in
[the app config.yaml file](../cehd/config/app/config.yml).

For an example of a site-level config file, [see the Ed Psych
config](../cehd/config/edpsych/config.yml).

We can add an arbitray number of files, or configuration values within these
files. then we just have to pass them in to the Configuration class to get an
array back

```
// Set up constants with paths to the individual config files
const BASE_PATH = __DIR__ . '/../../';
const BASE_CONFIG_PATH = BASE_PATH . 'cehd/config/app/config.yml';
const EDPSYCH_CONFIG_PATH = BASE_PATH . 'cehd/config/edpsych/config.yml';

// Instaniate a new bootstrap helper
$bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;

// The Bootstrap::bootstrap_config method will parse the YAML and return a
// single array
$global_config = $bootstrap_helper::bootstrap_config(
    [
        BASE_CONFIG_PATH,
        EDPSYCH_CONFIG_PATH,
    ]
);
```

Managing config this way allows us to do some simple logic as well, including
environment detection (development or production). It also makes config changes
or new configs easy to set up.
