<?php

namespace CEHD\App\Routes\CEHDRoutes;

require_once __DIR__ . "/../../cehd/app/CEHD/CEHDApp.php";

$base_app_config        = __DIR__ . "/../../cehd/config/app/config.yml";
$cehd_app_config        = __DIR__ . "/../../cehd/config/cehd/config.yml";
$nav_yaml_file          = __DIR__ . "/../../cehd/config/cehd/nav.yml";
$routes_yaml_file       = __DIR__ . "/../../cehd/config/cehd/routes.yml";
$redirects              = __DIR__ . "/../../cehd/config/cehd/redirects.yml";
$inline_styles_file     = __DIR__ . "/../../public/assets/dist/css/minified/inline.css";
$inline_svg_styles_file = __DIR__ . "/../../public/assets/dist/css/minified/svg.css";


$yaml_file_paths = [
    "configs" => [
        $base_app_config,
        $cehd_app_config,
    ],
    "nav" => $nav_yaml_file,
    "routes" => $routes_yaml_file,
    "redirects" => $redirects,
    "inline_styles" => $inline_styles_file,
    "inline_svg_styles" => $inline_svg_styles_file,
];

$app = new \CEHD\App\CEHD\CEHDApp($yaml_file_paths);

$app->router->respond("GET", "/", [$app, "homePageHandler"]);

$app->router->respond("GET", "/giving/gifts/", [$app, "giftsPageHandler"]);

$app->router->respond("GET", "/programs/", [$app, "programsPageHandler"]);

$app->router->respond("GET", "/patterns/v1/", [$app, "patternsHomePageHandler"]);

$app->router->respond("GET", "/topics/", [$app, "topicsPageHandler"]);

$app->router->respond("GET", "/topics/[:topic]/", [$app, "topicPageHandler"]);

$app->router->respond("GET", "/search/", [$app, "searchPageHandler"]);

$app->router->respond("GET", "/people/[:internetId]/", [$app, "personPageHandler"]);

$app->router->respond("GET", "/events/[i:id]/", [$app, "eventPageHandler"]);

// Services
$app->router->with("/services", function() use ($app)
{
    $app->router->respond("GET", "/search/", [$app, "searchEndpointHandler"]);

    $app->router->with("/people-tool", function() use ($app) {
        // returns a JSON list of people by tag combination
        $app->router->respond("GET", "/", [$app, "peopleEndpointHandler"]);

        // returns JSON data for a single person based on their internet id (X.500)
        $app->router->respond("GET", "/person/", [$app, "personEndpointHandler"]);
    });

    $app->router->with("/news", function() use ($app) {
        $app->router->respond("GET", "/feed/", [$app, "newsFeedEndpointHandler"]);
    });

    $app->router->with("/info-sessions", function() use ($app) {
        $app->router->respond("GET", "/", [$app, "infoSessionsEndpointHandler"]);
    });

    $app->router->with("/events", function() use ($app) {
        $app->router->respond("GET", "/", [$app, "eventEndpointHandler"]);
        $app->router->respond("GET", "/feed/", [$app, "eventsFeedEndpointHandler"]);
    });

    $app->router->with("/programs", function() use ($app) {
        $app->router->respond("GET", "/", [$app, "programsFeedEndpointHandler"]);
    });
});


// A handful of hard-coded redirects. These are special cases where we want
// possessive catching of all sub directory and files

$app->router->respond("GET", "/research/grantwriting[**:trailing]", function($request, $response, $service, $app)
    {
        $response->redirect("/research/grantsmanship/", 301)->send();
    });

$app->router->respond("GET", "/undergraduate/scholarships/[**:trailing]", function($request, $response, $service, $app)
    {
        $response->redirect("/undergraduate/scholarships/", 301)->send();
    });

$app->router->respond("GET", "/international[**:trailing]", function($request, $response, $service, $app)
    {
        $response->redirect("/global/", 301)->send();
    });

$app->router->respond("GET", "/direct/[**:trailing]", function($request, $response, $service, $app)
    {
        $response->redirect("/teaching/directrack/", 301)->send();
    });

$app->router->respond("GET", "/graduate/licensing[**:trailing]", function($request, $response, $service, $app)
    {
        $response->redirect("/teaching/license/", 301)->send();
    });

$app->router->respond("GET", "/alumni/[**:trailing]", function($request, $response, $service, $app)
    {
        $response->redirect("/alumni/", 301)->send();
    });
