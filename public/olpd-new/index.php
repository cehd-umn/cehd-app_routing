<?php

require_once __DIR__ . "/../../routes/olpd/routes.php";

$app->router->dispatch(null, $app->response);
