<?php

namespace CEHD\App\Routes\EdPsychStaticRoutes;

require_once __DIR__ . "/../../cehd/app/EdPsych/EdPsychApp.php";

$base_app_config     = __DIR__ . "/../../cehd/config/app/config.yml";
$edpsych_app_config  = __DIR__ . "/../../cehd/config/edpsych/config.yml";
$nav                 = __DIR__ . "/../../cehd/config/edpsych/nav.yml";
$routes              = __DIR__ . "/../../cehd/config/edpsych/routes.yml";
$redirects           = __DIR__ . "/../../cehd/config/edpsych/redirects.yml";
$redirects_camelcase = __DIR__ . "/../../cehd/config/edpsych/redirects_camelcase.yml";
$inline_styles_file  = __DIR__ . "/../../public/assets/dist/css/minified/inline.css";

$yaml_file_paths = [
    "configs" => [
        $base_app_config,
        $edpsych_app_config,
    ],
    "nav" => $nav,
    "routes" => $routes,
    "redirects" => $redirects,
    "redirects_camelcase" => $redirects_camelcase,
    "inline_styles" => $inline_styles_file,
];

$app = new \CEHD\App\EdPsych\EdPsychApp($yaml_file_paths);
