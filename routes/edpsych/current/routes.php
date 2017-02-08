<?php


$router->with('/current', function() use ($router) {
    /**
     * Redirects
     */
    $router->respond('GET', '/CSPP.html', function($request, $response, $service, $app) {
        $response->redirect($app->site_config['base_url'] . '/current/cspp/', 301)->send();
    });

    $router->respond('GET', '/Foundations.html', function($request, $response, $service, $app) {
        $response->redirect($app->site_config['base_url'] . '/current/foundations/', 301)->send();
    });

    $router->respond('GET', '/Oral-Prelim.html', function($request, $response, $service, $app) {
        $response->redirect($app->site_config['base_url'] . '/current/oral-prelim/', 301)->send();
    });

    $router->respond('GET', '/SchPsy.html', function($request, $response, $service, $app) {
        $response->redirect($app->site_config['base_url'] . '/current/school-psychology/', 301)->send();
    });

    $router->respond('GET', '/QME.html', function($request, $response, $service, $app) {
        $response->redirect($app->site_config['base_url'] . '/current/qme/', 301)->send();
    });

    $router->respond('GET', '/SpecialEd.html', function($request, $response, $service, $app) {
        $response->redirect($app->site_config['base_url'] . '/current/special-ed/', 301)->send();
    });

    $router->respond('GET', '/SpecEdStudentTeaching_practicum/SpecialEd - recruitment - practicum.html', function($request, $response, $service, $app) {
        $response->redirect($app->site_config['base_url'] . '/current/student-teaching-practicum/', 301)->send();
    });

    /**
     * Routes
     */
    $router->respond('GET', '/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/current/index.html', ["section" => "current"]);
    });

    $router->with('/school-psychology', function() use ($router) {

        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/school-psychology/index.html', ["section" => "current"]);
        });
        $router->respond('GET', '/spsa/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/school-psychology/spsa.html', ["section" => "current"]);
        });
    });

    $router->with('/psych-foundations', function() use ($router) {
        $router->respond('GET', '/prelim/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/psych-foundations/prelim.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/funding/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/psych-foundations/funding.html', ["section" => "programs-degrees"]);
        });
    });

    $router->with('/cspp', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/cspp/index.html', ["section" => "current"]);
        });

        $router->respond('GET', '/handbooks/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/cspp/handbooks/index.html', ["section" => "current"]);
        });
    });

    $router->respond('GET', '/foundations/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/current/foundations.html', ["section" => "current"]);
    });

    $router->respond('GET', '/oral-prelim/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/current/oral-prelim.html', ["section" => "current"]);
    });

    $router->with('/qme', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/qme/index.html', ["section" => "current"]);
        });

        $router->respond('GET', '/handbook/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/qme/handbook.html', ["section" => "current"]);
        });
    });

    $router->with('/special-ed', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            // @todo move this to special-ed/index.html
            echo $app->template->render('edpsych/current/special-ed/index.html', ["section" => "current"]);
        });

        $router->respond('GET', '/student-teaching-practicum/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/special-ed/student-teaching-practicum.html', ["section" => "current"]);
        });

        $router->respond('GET', '/autism-certificate/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/special-ed/autism-certificate.html', ["section" => "programs-degrees"]);
        });
    });

    $router->with('/handbook', function() use ($router) {
        /**
            * Redirects
            */
        $router->respond('GET', '/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/', 301)->send();
        });

        /**
            * Routes
            */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/handbook/index.html', ["section" => "current"]);
        });
    });
});
