<?php
namespace CEHD\App\Services\CEHD;

require_once __DIR__ . "/../../cehd/app/Services/Services.php";

$app = new \CEHD\App\Services\Services();

$app->router->dispatch(null, $app->response);
