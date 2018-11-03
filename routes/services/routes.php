<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once '../../vendor/autoload.php';
require_once '../../cehd/app/Services/ServicesApp.php';

$config = [
    "settings" => [
        "displayErrorDetails" => true,
    ]
];

$c = new \Slim\Container($config);

$c['errorHandler'] = function ($c) {
    return function ($request, $response, $exception) use ($c) {
        return $c['response']->withStatus(500)
                             ->withHeader('Content-Type', 'text/html')
                             ->write($exception->getMessage());
    };
};

$app = new \Slim\App($c);

// Sets up CORS headers so subdomains can make GET requests to these endpoints
$app->add(function ($req, $res, $next) {
    $response = $next($req, $res);
    return $response
            ->withHeader('Access-Control-Allow-Origin', '*')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET');
});

$app->get('/events/feed/', '\CEHD\Services\ServicesApp:eventsHandler');

$app->get('/event/', '\CEHD\Services\ServicesApp:eventHandler');

$app->get('/info-sessions/', '\CEHD\Services\ServicesApp:infoSessionsHandler');

$app->get('/news/feed/', '\CEHD\Services\ServicesApp:newsFeedHandler');

$app->get('/people-tool/', '\CEHD\Services\ServicesApp:peopleHandler');

$app->get('/people-tool/person/', '\CEHD\Services\ServicesApp:personHandler');

$app->get('/programs/', '\CEHD\Services\ServicesApp:programsHandler');

$app->get('/programs/v2/', '\CEHD\Services\ServicesApp:programsV2Handler');

$app->get('/search/', '\CEHD\Services\ServicesApp:searchHandler');
