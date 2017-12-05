<?php

namespace CEHD\App\Routes\Patterns;

require_once __DIR__ . "/../../cehd/app/Pattern/Controller.php";
require_once __DIR__ . "/../../cehd/app/App/Helpers.php";
require_once __DIR__ . "/../../cehd/app/middleware/RequestMiddleWare.php";
require_once __DIR__ . '/../../cehd/app/Card/PatternCard.php';

use \CEHD\App\Controller\Pattern\PatternController;
use \CEHD\App\RequestMiddleWare;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \CEHD\App\Helpers\Helpers;
use \CEHD\App\Card\PatternCard\PatternCards;
use \UMN\CEHD\Dict\Dict;

$inline_styles_file     = __DIR__ . "/../../public/assets/peloton/latest/dist/css/minified/inline.css";
$inline_svg_styles_file = __DIR__ . "/../../public/assets/peloton/latest/dist/css/minified/svg.css";
$inline_nav_styles_file = __DIR__ . "/../../public/assets/peloton/latest/dist/css/minified/nav.css";
$pattern_app_config     = __DIR__ . "/../../cehd/config/patterns/config.yml";
$patterns_file          = __DIR__ . "/../../cehd/config/patterns/patterns.yml";
$helpers = new Helpers();
$inline_styles = file_get_contents($inline_styles_file);
$inline_svg_styles = file_get_contents($inline_svg_styles_file);
$inline_nav_styles = file_get_contents($inline_nav_styles_file);
$app_config = $helpers->loadYaml($pattern_app_config);
$is_production_environment = $helpers->isProduction($helpers->getServerName());
$display_slim_errors = !$is_production_environment;
$container_config = [
    "settings" => [
        "displayErrorDetails" => $display_slim_errors,
    ]
];
$container = new \Slim\Container($container_config);

// @todo put the error handler in PatternController
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
$twig->addGlobal('inline_styles', $inline_styles);
$twig->addGlobal('inline_svg_styles', $inline_svg_styles);
$twig->addGlobal('inline_nav_styles', $inline_nav_styles);
$twig->addGlobal('js_base', $app_config["js_base"]);
$twig->addGlobal('css_base', $app_config["css_base"]);

$container['view'] = $helpers->configureTwigForSlim($container, $view, $app_config["base_url"]);
$patternCards = new PatternCards();
$patternCards->setCards($patterns_file);
$routes_data = $patternCards->buildRoutesFromPatternData();
$categories = $patternCards->getCategories();
sort($categories);
ksort($routes_data);
$container["patternCards"] = $routes_data;
$container["categories"] = $categories;
$container["redirects"] = new Dict();
$container["redirect_prepend"] = "";

$app = new \Slim\App($container);

$app->add(new RequestMiddleWare($container));

// Redirect to the v1 uri
$app->get("/", function($request, $response, $args) {
        return $response->withRedirect("v1/", 301);
    });

$app->get("/v1/", PatternController::class . ":home");


foreach ($routes_data as $route => $card) {
    $handler_name = "patternPageHandler";

    if ($card->handler) {
        $handler_name = $card->handler;
    }
    $app->get($route, PatternController::class . ":" . $handler_name);
}
