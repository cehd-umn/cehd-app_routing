<?php

require_once __DIR__ . "/../routes/cehd/routes.php";

$app->router->dispatch(null, $app->response);
