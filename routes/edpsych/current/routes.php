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

    $router->respond('GET', '/schoolpsych/spsa/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/current/schoolpsych/spsa.html', ["section" => "current"]);
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

    $router->respond('GET', '/school-psychology/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/current/school-psychology.html', ["section" => "current"]);
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
    });

    $router->with('/handbook', function() use ($router) {
        /**
            * Redirects
            */
        $router->respond('GET', '/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/', 301)->send();
        });

        $router->respond('GET', '/2011-12/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2011-12/', 301)->send();
        });

        $router->respond('GET', '/2011-12/admin.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2011-12/admin/', 301)->send();
        });

        $router->respond('GET', '/2011-12/doctorate.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2011-12/doctorate/', 301)->send();
        });

        $router->respond('GET', '/2011-12/masters.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2011-12/masters/', 301)->send();
        });

        $router->respond('GET', '/2011-12/planning.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2011-12/planning/', 301)->send();
        });

        $router->respond('GET', '/2011-12/registration.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2011-12/registration/', 301)->send();
        });

        $router->respond('GET', '/2011-12/resources.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2011-12/resources/', 301)->send();
        });

        $router->respond('GET', '/2012-13/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2012-13/', 301)->send();
        });

        $router->respond('GET', '/2012-13/admin.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2012-13/admin/', 301)->send();
        });

        $router->respond('GET', '/2012-13/doctorate.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2012-13/doctorate/', 301)->send();
        });

        $router->respond('GET', '/2012-13/masters.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2012-13/masters/', 301)->send();
        });

        $router->respond('GET', '/2012-13/planning.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2012-13/planning/', 301)->send();
        });

        $router->respond('GET', '/2012-13/registration.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2012-13/registration/', 301)->send();
        });

        $router->respond('GET', '/2012-13/resources.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/handbook/2012-13/resources/', 301)->send();
        });

        /**
            * Routes
            */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/handbook/index.html', ["section" => "current"]);
        });

        $router->with('/2011-12', function() use ($router) {
            $router->respond('GET', '/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2011-12/index.html', ["section" => "current"]);
            });

            $router->respond('GET', '/admin/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2011-12/admin.html', ["section" => "current"]);
            });

            $router->respond('GET', '/doctorate/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2011-12/doctorate.html', ["section" => "current"]);
            });

            $router->respond('GET', '/masters/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2011-12/masters.html', ["section" => "current"]);
            });

            $router->respond('GET', '/planning/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2011-12/planning.html', ["section" => "current"]);
            });

            $router->respond('GET', '/registration/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2011-12/registration.html', ["section" => "current"]);
            });

            $router->respond('GET', '/resources/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2011-12/resources.html', ["section" => "current"]);
            });

            $router->respond('GET', '/specialist/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2011-12/specialist.html', ["section" => "current"]);
            });
        });

        $router->with('/2012-13', function() use ($router) {
            $router->respond('GET', '/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2012-13/index.html', ["section" => "current"]);
            });

            $router->respond('GET', '/admin/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2012-13/admin.html', ["section" => "current"]);
            });

            $router->respond('GET', '/doctorate/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2012-13/doctorate.html', ["section" => "current"]);
            });

            $router->respond('GET', '/masters/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2012-13/masters.html', ["section" => "current"]);
            });

            $router->respond('GET', '/planning/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2012-13/planning.html', ["section" => "current"]);
            });

            $router->respond('GET', '/registration/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2012-13/registration.html', ["section" => "current"]);
            });

            $router->respond('GET', '/resources/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2012-13/resources.html', ["section" => "current"]);
            });

            $router->respond('GET', '/specialist/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/current/handbook/2012-13/specialist.html', ["section" => "current"]);
            });
        });
    });
});
