<?php
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

require '../../vendor/autoload.php';
require '../../cehd/app/Services/ServicesApp.php';

$app = new \Slim\App(
[
    "settings" => [
        "displayErrorDetails" => true,
    ]
]);

$app->get('/events/', '\CEHD\Services\ServicesApp:eventsHandler');

$app->get('/event/', '\CEHD\Services\ServicesApp:eventHandler');
