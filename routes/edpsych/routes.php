<?php

namespace CEHD\App\Routes\EdPsychRoutes;

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

// Search page
$app->router->respond('GET', '/edpsych/search/', [$app, 'searchPageHandler']);

$app->router->respond(
    'GET',
    '/edpsych/programs/courses/[asl|qme|psyf:course]/',
    [$app, 'programsCoursePageHandler']
);

$app->router->with('/edpsych/people', function () use ($app) {
    $app->router->respond('GET', '/', [$app, 'peoplePageHandler']);
    $app->router->respond(
        'GET',
        '/[:internetId]/',
        [$app, 'personPageHandler']
    );
});

// Services endpoints
$app->router->with('/edpsych/services', function () use ($app) {
    $app->router->respond('GET', '/search/', [$app, 'searchEndpointHandler']);

    $app->router->with('/people-tool', function () use ($app) {
        $app->router->respond('GET', '/', [$app, 'peopleEndpointHandler']);
        $app->router->respond('GET', '/person/', [$app, 'personEndpointHandler']);
    });

    $app->router->with('/news', function () use ($app) {
        $app->router->respond('GET', '/feed/', [$app, 'newsFeedEndpointHandler']);
    });

    $app->router->with('/events', function () use ($app) {
        $app->router->respond('GET', '/feed/', [$app, 'eventsFeedEndpointHandler']);
    });

    $app->router->with('/programs', function () use ($app) {
        $app->router->respond('GET', '/', [$app, 'programsFeedEndpointHandler']);
    });
});
