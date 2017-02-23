<?php
/**
 * PROGRAMS AND DEGREES Routes
 */
$router->with('/programs/Foundations', function() use ($router) {
    $router->respond('GET', '*', function($request, $response, $service, $app) {
        /**
            This is really hacky, but since past URLs were case insensitive, we
            have to match first against "Programs" (with uppercase P) and
            redirect to where the Foundations url (with uppercase F) redirect
            will hit and go to the right place. This is just one reason why we
            shouldn't have case insensitive URLs.
        */
        $replace_location = strpos($request->uri(), "programs");
        $new_uri = substr_replace($request->uri(), "P", $replace_location, 1);
        $response->redirect($new_uri, 301)->send();
    });
});

$router->with('/programs/SchoolPsych', function() use ($router) {
    $router->respond('GET', '*', function($request, $response, $service, $app) {
        /**
            This is really hacky, but since past URLs were case insensitive, we
            have to match first against "Programs" (with uppercase P) and
            redirect to where the Foundations url (with uppercase F) redirect
            will hit and go to the right place. This is just one reason why we
            shouldn't have case insensitive URLs.
        */
        $replace_location = strpos($request->uri(), "programs");
        $new_uri = substr_replace($request->uri(), "P", $replace_location, 1);
        $response->redirect($new_uri, 301)->send();
    });
});

$router->with('/programs', function() use ($router) {
    // Redirects are in their own file
    require_once BASE_PATH . 'routes/edpsych/programs/redirects.php';

    $router->respond('GET', '/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/index.html', ["section" => "programs-degrees"]);
    });

    $router->respond('GET', '/asl/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/asl/index.html', ["section" => "programs-degrees"]);
    });

    $router->respond('GET', '/asl/faq/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/asl/faq.html', ["section" => "programs-degrees"]);
    });

    $router->respond('GET', '/asl/placement-assessment/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/asl/placement-assessment.html', ["section" => "programs-degrees"]);
    });

    $router->respond('GET', '/courses/[asl|qme|psyf:course]/', function($request, $response, $service, $app) {
        // values for the "p" query parameter in the JavaScript request
        $p_params = [
            "asl" => [
                "p" => "EPSY_ASL",
                "title" => "American Sign Language",
            ],
            "qme" => [
                "p" => "EPSY_PSE",
                "title" => "Quantitative Methods in Education",
            ],
            "psyf" => [
                "p" => "EPSY_PSYF",
                "title" => "Psychological Foundations of Education",
            ],
        ];
        $course = $request->param('course');
        $template_vars = [
            "section" => "programs-degrees",
            "p_param" => $p_params[$course]["p"],
            "title_text" => $p_params[$course]["title"],
        ];

        echo $app->template->render('edpsych/programs/courses/course.html', $template_vars);
    });

    /**
     * CSPP Section
     */
    $router->with('/cspp', function() use ($router) {
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

    // Redirects for *this* camelcase version,i.e., programs/SpecialEd/ as opposed to Programs/SpecialEd
    $router->with('/SpecialEd', function() use ($router) {
        $html_filenames = [
            "SpEd-MEd-teacherlicensure.html",
            "DHH-licensure.html",
        ];

        foreach ($html_filenames as $name)
        {
            $router->with('/' . $name, function() use ($router) {
                $router->respond('GET', '*', function($request, $response, $service, $app) {
                    $response->redirectToURI($request->uri());
                });
            });
        }
    });

    $router->with('/specialed', function() use ($router) {
        // More redirects

        // If this ever needs to change, make sure you at least review the same
        // list in routes/edpsych/programs/redirects.php
        $html_filenames = [
            "default.html",
            "videos.html",
            "sped-bs.html",
            "SpEd-BS.html",
            "sped-med-teacherlicensure.html",
            "phd.html",
            "masters.html",
            "abs-licensure.html",
            "asd.html",
            "ASD.html",
            "dhh-licensure.html",
            "dd-licensure.html",
            "esce-licensure.html",
            "ld-licensure.html",
            "prelim-study.html",
            "license.html",
        ];

        foreach ($html_filenames as $name)
        {
            $router->with('/' . $name, function() use ($router) {
                $router->respond('GET', '*', function($request, $response, $service, $app) {
                    $response->redirectToURI($request->uri());
                });
            });
        }

        $router->respond('get', '/ebd-residency-based/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/specialed/ebd-residency-based/', 301)->send();
        });

        $router->respond('get', '/certificate/autism.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/specialed/certificate/autism/', 301)->send();
        });

        $router->respond('get', '/certificate/autism-registration.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/current/special-ed/autism-certificate/', 301)->send();
        });

        // Actual routes
        $routes = [
            "abs-licensure",
            "asd",
            "dd-licensure",
            "dhh-licensure",
            "ebd-residency-based",
            "esce-licensure",
            "ld-licensure",
            "masters",
            "phd",
            "sped-bs",
            "sped-med-teacherlicensure",
            "videos",
            "prelim-study",
            "license",
        ];

        foreach ($routes as $route)
        {
            $router->with('/' . $route, function() use ($router) {
                // @todo make this anonymous callback an object to handle these
                $router->respond('GET', '/', function($request, $response, $service, $app) {
                    // Get the template name based on the uri
                    $file_path = 'edpsych/programs/specialed/';
                    $template_name = $response->templateNameFromURI($request->uri());
                    $template_path = $file_path . $template_name;

                    // render the template
                    echo $app->template->render($template_path, ["section" => "programs-degrees"]);
                });
            });

        };

        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/specialed/index.html', ["section" => "programs-degrees"]);
        });

        $router->with('/certificate', function() use ($router) {
            $router->with('/autism', function() use ($router) {
                $router->respond('GET', '/', function($request, $response, $service, $app) {
                    echo $app->template->render('edpsych/programs/specialed/certificate/autism/index.html', ["section" => "programs-degrees"]);
                });
            });
        });
    });

    $router->with('/foundations', function() use ($router) {

        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/index.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/learning/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/learning.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/social-psych/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/social-psych.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/talent/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/talent.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/5135/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations/5135.html', ["section" => "programs-degrees"]);
        });

        $router->with('/funding', function() use ($router) {
            $router->respond('GET', '/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/programs/foundations/funding/index.html', ["section" => "programs-degrees"]);
            });

            $router->respond('GET', '/psyf-assistantships/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/programs/foundations/funding/psyf-assistantships.html', ["section" => "programs-degrees"]);
            });

            $router->respond('GET', '/merwin/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/programs/foundations/funding/merwin.html', ["section" => "programs-degrees"]);
            });

            $router->respond('GET', '/yackel/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/programs/foundations/funding/yackel.html', ["section" => "programs-degrees"]);
            });

            $router->respond('GET', '/policies/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/programs/foundations/funding/policies.html', ["section" => "programs-degrees"]);
            });

            $router->respond('GET', '/ga/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/programs/foundations/funding/ga.html', ["section" => "programs-degrees"]);
            });
        });
    }); // end programs/foundations

    $router->respond('GET', '/graduate-minor/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/programs/graduate-minor.html', ["section" => "programs-degrees"]);
    });

    $router->with('/minor', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            // @todo move this template to programs/minor/index.html
            echo $app->template->render('edpsych/programs/minor.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/curriculum/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/minor/curriculum.html', ["section" => "programs-degrees"]);
        });
    });
});
