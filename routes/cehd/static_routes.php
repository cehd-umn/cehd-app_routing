<?php

namespace CEHD\App\Routes\StaticRoutes;

require_once __DIR__ . "../app/App/App.php";

// @todo write these yaml files
$base_app_config  = __DIR__ . "../../config/app/config.yml";
$cehd_app_config  = __DIR__ . "../../config/cehd/config.yml";
$nav_yaml_file    = __DIR__ . "../../config/cehd/nav.yml";
$routes_yaml_file = __DIR__ . "../../config/cehd/routes.yml";


$yaml_file_paths = [
    "configs" => [
        $base_app_config,
        $cehd_app_config,
    ],
    "nav" => $nav_yaml_file,
    "routes" => $routes_yaml_file,
];

$app = new \CEHD\App\App\BaseApp($yaml_file_paths);
