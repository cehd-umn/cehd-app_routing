<?php
/**
 * Programs Redirects
 */
$router->with('/Programs', function() use ($router) {
    // $router->respond('GET', '[/|/minor/|/ASL/]', function($request, $response, $service, $app) {
    //     $response->redirectToLowercaseURI($request->uri());
    // });

    // $router->respond('GET', '/minor/default.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/minor/', 301)->send();
    // });

    // $router->respond('GET', '/ASL/default.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/asl/', 301)->send();
    // });

    // $router->respond('GET', '/ASL/faq.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/asl/faq/', 301)->send();
    // });

    // $router->respond('GET', '/ASL/ASL-Placement-Assessment.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/asl/placement-assessment/', 301)->send();
    // });

    // $router->respond('GET', '/Courses/ASL.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/courses/asl/', 301)->send();
    // });

    // $router->respond('GET', '/Courses/PsyF.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/courses/psyf/', 301)->send();
    // });

    // $router->respond('GET', '/Courses/QME.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/courses/qme/', 301)->send();
    // });

    // $router->respond('GET', '/CSPP/', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/cspp/', 301)->send();
    // });

    // $router->respond('GET', '/CSPP/default.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/cspp/', 301)->send();
    // });

    // $router->respond('GET', '/CSPP/Hansen.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/cspp/hansen/', 301)->send();
    // });

    // $router->respond('GET', '/CSPP/Sullivan.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/cspp/sullivan/', 301)->send();
    // });

    // $router->respond('GET', '/CSPP/Handbooks/default.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/current/cspp/handbooks/', 301)->send();
    // });

    // $router->respond('GET', '/Foundations/default.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/foundations/', 301)->send();
    // });

    // $router->respond('GET', '/Foundations/', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/foundations/', 301)->send();
    // });

    // $router->respond('GET', '/Foundations/Funding/default.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/foundations/funding/', 301)->send();
    // });

    // $router->respond('GET', '/Foundations/learning.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/foundations/learning/', 301)->send();
    // });

    // $router->respond('GET', '/Foundations/prelim.html', function($request, $response, $service, $app) {
    //     $response->redirectToURI($request->uri());
    // });

    // $router->respond('GET', '/Foundations/social-psych.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/foundations/social-psych/', 301)->send();
    // });

    // $router->respond('GET', '/Foundations/certificate/talent.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/foundations/talent/', 301)->send();
    // });

    //$router->respond('GET', '/Foundations/certificate/Talent.html', function($request, $response, $service, $app) {
    //    $response->redirect($app->site_config['base_url'] . '/programs/foundations/talent/', 301)->send();
    //});

    //$router->respond('GET', '/Foundations/5135.html', function($request, $response, $service, $app) {
    //    $response->redirect($app->site_config['base_url'] . '/programs/foundations/5135/', 301)->send();
    //});

    //$router->respond('GET', '/CSPP/handbooks/', function($request, $response, $service, $app) {
    //    $response->redirect($app->site_config['base_url'] . '/current/cspp/handbooks/', 301)->send();
    //});

    // $router->respond('GET', '/QME/', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/qme/', 301)->send();
    // });

    // $router->respond('GET', '/QME/default.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/qme/', 301)->send();
    // });

    // $router->respond('GET', '/QME/handbook.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/current/qme/handbook/', 301)->send();
    // });

    // $router->respond('GET', '/QME/job-placement.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/qme/job-placement/', 301)->send();
    // });

    // $router->respond('GET', '/QME/minor.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/qme/minor/', 301)->send();
    // });

    // $router->respond('GET', '/QME/stats-ed-courses.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/qme/stats-ed/courses/', 301)->send();
    // });

    // $router->respond('GET', '/QME/stats-intro.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/qme/stats-intro/', 301)->send();
    // });

    // $router->respond('GET', '/Graduate-Minor.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/graduate-minor/', 301)->send();
    // });

    // $router->respond('GET', '/SchoolPsych/', function($request, $response, $service, $app) {
    //     $response->redirectToLowercaseURI($request->uri());
    // });

    // $router->respond('GET', '/SchoolPsych/default.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/', 301)->send();
    // });

    // $router->respond('GET', '/SchoolPsych/students.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/', 301)->send();
    // });

    // $router->respond('GET', '/SchoolPsych/focus.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/focus/', 301)->send();
    // });

    // $router->respond('GET', '/SchoolPsych/diversity.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/diversity/', 301)->send();
    // });

    // $router->respond('GET', '/SchoolPsych/outcomes.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/outcomes/', 301)->send();
    // });

    // $router->respond('GET', '/SchoolPsych/master.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/master/', 301)->send();
    // });

    // $router->respond('GET', '/SchoolPsych/doctor.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/doctor/', 301)->send();
    // });

    // $router->respond('GET', '/SchoolPsych/SPSA.html', function($request, $response, $service, $app) {
    //     $response->redirect($app->site_config['base_url'] . '/current/school-psychology/', 301)->send();
    // });

    // $router->respond('GET', '/minor/curriculum.html', function($request, $response, $service, $app) {
    //     $response->redirectToURI($request->uri());
    // });

    // $router->with('/[f|F]oundations', function() use ($router) {
    //     $router->respond('GET', '/', function($request, $response, $service, $app) {
    //         echo $app->template->render('edpsych/programs/foundations/index.html', ["section" => "programs-degrees"]);
    //     });

    //     $router->respond('GET', '/Funding/PsyFassistantships.html', function($request, $response, $service, $app) {
    //         $response->redirect($app->site_config['base_url'] . '/programs/foundations/funding/psyf-assistantships/', 301)->send();
    //     });

    //     $router->with('/Funding', function() use ($router) {
    //         $html_filenames = [
    //             "Merwin.html",
    //             "Yackel.html",
    //             "policies.html",
    //             "GA.html",
    //         ];

    //         foreach ($html_filenames as $name)
    //         {
    //             $router->with('/' . $name, function() use ($router) {
    //                 $router->respond('GET', '*', function($request, $response, $service, $app) {
    //                     $response->redirectToURI($request->uri());
    //                 });
    //             });
    //         }
    //     });
    // });

    // $router->with('/SpecialEd', function() use ($router) {
    //     $router->respond('GET', '/', function($request, $response, $service, $app) {
    //         $response->redirectToLowercaseURI($request->uri());
    //     });

    //     // If this ever needs to change, make sure you at least review the same
    //     // list in routes/edpsych/programs/routes.php
    //     $html_filenames = [
    //         "default.html",
    //         "videos.html",
    //         "SpEd-BS.html",
    //         "SpEd-MEd-teacherlicensure.html",
    //         "PhD.html",
    //         "masters.html",
    //         "ABS-licensure.html",
    //         "ASD.html",
    //         "DHH-licensure.html",
    //         "dd-licensure.html",
    //         "esce-licensure.html",
    //         "ld-licensure.html",
    //         "prelim-study.html",
    //         "license.html",
    //     ];

    //     foreach ($html_filenames as $name)
    //     {
    //         $router->with('/' . $name, function() use ($router) {
    //             $router->respond('GET', '*', function($request, $response, $service, $app) {
    //                 $response->redirectToURI($request->uri());
    //             });
    //         });
    //     }

    //     $router->respond('GET', '/EBD-residency-based/default.html', function($request, $response, $service, $app) {
    //         $response->redirect($app->site_config['base_url'] . '/programs/specialed/ebd-residency-based/', 301)->send();
    //     });

    //     $router->respond('GET', '/certificate/Autism.html', function($request, $response, $service, $app) {
    //         $response->redirect($app->site_config['base_url'] . '/programs/specialed/certificate/autism/', 301)->send();
    //     });

    //     $router->respond('GET', '/certificate/autism-registration.html', function($request, $response, $service, $app) {
    //         $response->redirect($app->site_config['base_url'] . '/current/special-ed/autism-certificate/', 301)->send();
    //     });

    // });
});
