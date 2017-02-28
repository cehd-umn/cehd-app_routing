<?php

require_once __DIR__ . "/../routes/cehd/static_routes.php";

$app->router->dispatch(null, $app->response);
