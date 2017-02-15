<?php

$router->with('/Current', function() use ($router) {
    $html_filenames = [
        "CSPP.html",
        "Foundations.html",
        "QME.html",
    ];

    foreach ($html_filenames as $name)
    {
        $router->with('/' . $name, function() use ($router) {
            $router->respond('GET', '*', function($request, $response, $service, $app) {
                $response->redirectToURI($request->uri());
            });
        });
    }

    $router->respond('GET', '/', function($request, $response, $service, $app) {
        $response->redirectToLowercaseURI($request->uri());
    });
});
