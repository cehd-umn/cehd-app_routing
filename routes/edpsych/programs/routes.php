<?php
/**
 * PROGRAMS AND DEGREES Routes
 */
$router->with('/programs', function() use ($router) {
    // Redirects are in their own file
    require_once BASE_PATH . 'routes/edpsych/programs/redirects.php';

    $router->respond('GET', '/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/index.html', ["section" => "programs-degrees"]);
    });

    $router->respond('GET', '/asl/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/asl.html', ["section" => "programs-degrees"]);
    });

    $router->respond('GET', '/asl/faq/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/asl-faq.html', ["section" => "programs-degrees"]);
    });

    $router->respond('GET', '/asl/placement-assessment/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/asl-placement-assessment.html', ["section" => "programs-degrees"]);
    });

    $router->respond('GET', '/courses/[asl|qme|psyf:course]/', function($request, $response, $service, $app) {
        // values for the "p" query parameter in the JavaScript request
        $p_params = [
            "asl" => "EPSY_ASL",
            "qme" => "EPSY_PSE",
            "psyf" => "EPSY_PSYF",
        ];
        $course = $request->param('course');
        $template_vars = [
            "section" => "programs-degrees",
            "p_param" => $p_params[$course],
        ];

        echo $app->template->render('edpsych/programs/courses/course.html', $template_vars);
    });

    /**
     * CSPP Section
     */
    $router->with('/cspp', function() use ($router) {
        /**
            * Redirects
            */
        $router->respond('GET', '/Hansen.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/cspp/hansen/', 301)->send();
        });

        $router->respond('GET', '/Sullivan.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/cspp/sullivan/', 301)->send();
        });

        /**
         * Routes
         */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/cspp/index.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/hansen/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/cspp/hansen.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/sullivan/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/cspp/sullivan.html', ["section" => "programs-degrees"]);
        });
    }); // end /cspp subsection

    $router->with('/qme', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/qme/index.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/job-placement/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/qme/job-placement.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/minor/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/qme/minor.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/stats-ed-courses/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/qme/stats-ed-courses.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/stats-intro/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/qme/stats-intro.html', ["section" => "programs-degrees"]);
        });
    }); // end programs/qme

    /**
     * School Psych section
     */
    $router->with('/schoolpsych', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych/index.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/students/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych/students.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/focus/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych/focus.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/diversity/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych/diversity.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/doctor/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych/doctor.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/master/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych/master.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/outcomes/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych/outcomes.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/pride-fellow/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych/pride-fellow.html', ["section" => "programs-degrees"]);
        });
    }); // end schoolpsych section

    $router->respond('GET', '/specialed/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/specialed.html', ["section" => "programs-degrees"]);
    });

    $router->with('/foundations', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/index.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/learning/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/learning.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/school-psych/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/school-psych.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/talent/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/talent.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/5135/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/5135.html', ["section" => "programs-degrees"]);
        });
    }); // end programs/foundations

    $router->respond('GET', '/graduate-minor/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/graduate-minor.html', ["section" => "programs-degrees"]);
    });

    $router->respond('GET', '/minor/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/minor.html', ["section" => "programs-degrees"]);
    });
});
