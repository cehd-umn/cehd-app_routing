<?php

namespace CEHD\App\Routes\OLPDRoutes;

use \CEHD\App\OLPD\OLPDApp;
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once __DIR__ . "/../../cehd/app/OLPD/OLPDApp.php";

$inline_styles_file     = __DIR__ . "/../../public/assets/dist/css/minified/inline.css";
$inline_svg_styles_file = __DIR__ . "/../../public/assets/dist/css/minified/svg.css";
$nav_yaml_file          = __DIR__ . "/../../cehd/config/olpd/nav.yml";
$olpd_app_config        = __DIR__ . "/../../cehd/config/olpd/config.yml";
$redirects              = __DIR__ . "/../../cehd/config/olpd/redirects.yml";
$routes_yaml_file       = __DIR__ . "/../../cehd/config/olpd/routes.yml";
$inline_styles = file_get_contents($inline_styles_file);
$inline_svg_styles = file_get_contents($inline_svg_styles_file);

$olpdApp = new OLPDApp();

$app_config = $olpdApp->loadYaml($olpd_app_config);

$nav_items = $olpdApp->loadYaml($nav_yaml_file);

$container_config = [
    "settings" => [
        "displayErrorDetails" => true,
    ]
];

$container = new \Slim\Container($container_config);

$container['errorHandler'] = function ($container) {
    return function ($request, $response, $exception) use ($container) {
        return $container['response']->withStatus(500)
                             ->withHeader('Content-Type', 'text/html')
                             ->write($exception->getMessage());
    };
};

// Register component on container
$container['view'] = $olpdApp->configureTwigForSlim(
    $container,
    $app_config,
    $app_config["filter_method_names"],
    $nav_items,
    $app_config["autoescape"],
    $app_config["autoreload"],
    $app_config["debug"],
    $inline_styles,
    $inline_svg_styles,
    $app_config["base_url"]
);

// $container['view'] = function ($container) {
//     $view = new \Slim\Views\Twig('path/to/templates', [
//         'cache' => 'path/to/cache'
//     ]);
//
//     // Instantiate and add Slim specific extension
//     $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
//     $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));
//
//     return $view;
// };

$app = new \Slim\App($container);

$app->get("/", function (Request $request, Response $response)
{
    $response->getBody()->write("Hello world!");
    return $response;
});
