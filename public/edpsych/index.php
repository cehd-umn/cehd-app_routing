<?php
/**
 * The Ed Psych site app
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @version 0.1.0
 */

require_once __DIR__ . "/../../routes/edpsych/routes.php";

$app->router->dispatch(null, $app->response);
