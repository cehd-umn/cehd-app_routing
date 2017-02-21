<?php

require_once __DIR__ . "/../routes/cehd/static_routes.php";
require_once __DIR__ . '/../cehd/app/Response/BaseResponse.php';

$app->router->dispatch(null, new \CEHD\App\Response\BaseResponse());
