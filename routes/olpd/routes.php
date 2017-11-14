<?php

namespace CEHD\App\Routes\OLPDRoutes;

require_once __DIR__ . "/../../cehd/app/OLPD/OLPDApp.php";
require_once __DIR__ . "/../../cehd/app/App/Helpers.php";
require_once __DIR__ . "/../../cehd/app/middleware/TrailingSlash.php";

use \CEHD\App\OLPD\OLPDApp;
use \CEHD\App\AddTrailingSlash;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \CEHD\App\Helpers\Helpers;
use \UMN\CEHD\Dict\Dict;

// @todo change the Peloton version number once these styles work
$inline_styles_file     = __DIR__ . "/../../public/assets/peloton/latest/dist/css/minified/inline.css";
$inline_svg_styles_file = __DIR__ . "/../../public/assets/peloton/latest/dist/css/minified/svg.css";
$inline_nav_styles_file = __DIR__ . "/../../public/assets/peloton/latest/dist/css/minified/nav.css";
$nav_yaml_file          = __DIR__ . "/../../cehd/config/olpd/nav.yml";
$olpd_app_config        = __DIR__ . "/../../cehd/config/olpd/config.yml";
$redirects              = __DIR__ . "/../../cehd/config/olpd/redirects.yml";
$routes_yaml_file       = __DIR__ . "/../../cehd/config/olpd/routes.yml";
$helpers = new Helpers();
$inline_styles = file_get_contents($inline_styles_file);
$inline_svg_styles = file_get_contents($inline_svg_styles_file);
$inline_nav_styles = file_get_contents($inline_nav_styles_file);
$app_config = $helpers->loadYaml($olpd_app_config);
$nav_items = $helpers->loadYaml($nav_yaml_file);
$routes = $helpers->loadYaml($routes_yaml_file);
$is_production_environment = $helpers->isProduction($helpers->getServerName());
$display_slim_errors = !$is_production_environment;
$container_config = [
    "settings" => [
        "displayErrorDetails" => $display_slim_errors,
    ]
];
$container = new \Slim\Container($container_config);

// @todo put the error handler in OLPDApp
$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        return $container['response']->withStatus(500)
                             ->withHeader('Content-Type', 'text/html')
                             ->write($exception->getMessage());
    };
};

$view = $helpers->setUpTwig(
    $app_config,
    $app_config["filter_method_names"],
    $app_config["autoescape"],
    $app_config["autoreload"],
    $app_config["debug"]
);

$twig = $view->getEnvironment();
$twig->addGlobal('nav_items', $nav_items);
$twig->addGlobal('inline_styles', $inline_styles);
$twig->addGlobal('inline_svg_styles', $inline_svg_styles);
$twig->addGlobal('inline_nav_styles', $inline_nav_styles);
$twig->addGlobal("gsa_sites", $app_config["search_sites"]);

$container['view'] = $helpers->configureTwigForSlim($container, $view, $app_config["base_url"]);
$container["routes"] = new Dict($routes);

$container["notFoundHandler"] = function ($container)
{
    return function($request, $response) use ($container)
    {
        $container["view"]->render($response, "olpd/error/404.html", []);
        return $container["response"]->withStatus(404);
    };
};

$app = new \Slim\App($container);

// Adds trailing slash to GET requests that don't have them
$app->add(new AddTrailingSlash());

// Load all the routes from the routes file
foreach ($routes as $route => $route_info) {
    if ($route_info["publish"] || !$is_production_environment) {
        $app->get($route, OLPDApp::class . ":renderTemplateFromRoute");
    }
}

$app->get("/data/people/", OLPDApp::class . ":personExtraData");
$app->get("/people/{internet_id}/", OLPDApp::class . ":personPage");
$app->get("/search/", OLPDApp::class . ":searchPage");
