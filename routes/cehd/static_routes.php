<?php

namespace CEHD\App\Routes\StaticRoutes;

require_once __DIR__ . "/../../cehd/app/App/App.php";

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

$app = new \CEHD\App\App\BaseApp($yaml_file_paths);
