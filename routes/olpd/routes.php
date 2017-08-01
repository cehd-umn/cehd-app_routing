<?php

namespace CEHD\App\Routes\OLPDRoutes;

require_once __DIR__ . "/../../cehd/app/OLPD/OLPDApp.php";

$base_app_config        = __DIR__ . "/../../cehd/config/app/config.yml";
$olpd_app_config        = __DIR__ . "/../../olpd/config/cehd/config.yml";
$nav_yaml_file          = __DIR__ . "/../../olpd/config/cehd/nav.yml";
$routes_yaml_file       = __DIR__ . "/../../olpd/config/cehd/routes.yml";
$redirects              = __DIR__ . "/../../olpd/config/cehd/redirects.yml";
$inline_styles_file     = __DIR__ . "/../../public/assets/dist/css/minified/inline.css";
$inline_svg_styles_file = __DIR__ . "/../../public/assets/dist/css/minified/svg.css";


$yaml_file_paths = [
    "configs" => [
        $base_app_config,
        $olpd_app_config,
    ],
    "nav" => $nav_yaml_file,
    "routes" => $routes_yaml_file,
    "redirects" => $redirects,
    "inline_styles" => $inline_styles_file,
    "inline_svg_styles" => $inline_svg_styles_file,
];

$app = new \CEHD\App\OLPD\OLPDApp($yaml_file_paths);
