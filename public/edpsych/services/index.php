<?php
namespace CEHD\App\Services\EdPsych;

require_once __DIR__ . "/../../../cehd/app/Services/Services.php";

$app = new \CEHD\App\Services\Services("/edpsych");

$app->router->dispatch(null, $app->response);
