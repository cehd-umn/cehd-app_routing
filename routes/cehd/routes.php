<?php

namespace CEHD\App\Routes\CEHDRoutes;

require_once __DIR__ . "/../../cehd/app/CEHD/CEHDApp.php";

$base_app_config    = __DIR__ . "/../../cehd/config/app/config.yml";
$cehd_app_config    = __DIR__ . "/../../cehd/config/cehd/config.yml";
$nav_yaml_file      = __DIR__ . "/../../cehd/config/cehd/nav.yml";
$routes_yaml_file   = __DIR__ . "/../../cehd/config/cehd/routes.yml";
$inline_styles_file = __DIR__ . "/../../public/assets/dist/css/minified/inline.css";


$yaml_file_paths = [
    "configs" => [
        $base_app_config,
        $cehd_app_config,
    ],
    "nav" => $nav_yaml_file,
    "routes" => $routes_yaml_file,
    "inline_styles" => $inline_styles_file,
];

$app = new \CEHD\App\CEHD\CEHDApp($yaml_file_paths);

$app->router->respond('GET', '/programs/', [$app, 'programsPageHandler']);

$app->router->respond('GET', '/search/', [$app, 'searchPageHandler']);

$app->router->respond('GET', '/sitemap.xml', [$app, 'sitemapHandler']);

// Services
// @todo move all services endpoints here
$app->router->with('/services', function() use ($app)
{
    $app->router->respond('GET', '/search/', [$app, 'searchEndpointHandler']);

    $app->router->with('/people-tool', function() use ($app) {
        $app->router->respond('GET', '/', [$app, 'peopleEndpointHandler']);
        $app->router->respond('GET', '/person/', [$app, 'personEndpointHandler']);
    });

    $app->router->with('/news', function() use ($app) {
        $app->router->respond('GET', '/feed/', [$app, 'newsFeedEndpointHandler']);
    });

    $app->router->with('/events', function() use ($app) {
        $app->router->respond('GET', '/feed/', [$app, 'eventsFeedEndpointHandler']);
    });

    $app->router->with('/programs', function() use ($app) {
        $app->router->respond('GET', '/', [$app, 'programsFeedEndpointHandler']);
    });
});
