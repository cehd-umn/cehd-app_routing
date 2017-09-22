<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require_once '../../vendor/autoload.php';
require_once '../../cehd/app/Services/ServicesApp.php';

$app = new \Slim\App(
[
    "settings" => [
        "displayErrorDetails" => true,
    ]
]
);

$app->get('/events/feed/', '\CEHD\Services\ServicesApp:eventsHandler');

$app->get('/event/', '\CEHD\Services\ServicesApp:eventHandler');

$app->get('/info-sessions/', '\CEHD\Services\ServicesApp:infoSessionsHandler');

$app->get('/news/feed/', '\CEHD\Services\ServicesApp:newsFeedHandler');

$app->get('/people-tool/', '\CEHD\Services\ServicesApp:peopleHandler');

$app->get('/people-tool/person/', '\CEHD\Services\ServicesApp:personHandler');
