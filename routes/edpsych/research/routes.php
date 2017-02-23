<?php
/**
 * Research
 */
$router->with('/research', function() use ($router) {
    /**
     * Redirects
     */
    require_once BASE_PATH . 'routes/edpsych/research/redirects.php';

    /**
     * Routes
     */
    $router->respond('GET', '/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/research/index.html', ["section" => "research"]);
    });

    $html_filenames = [
        "family-academy.html",
        "ready-to-succeed.html",
        "pnrc.html",
        "500-under-five.html",
        "fresh-garden.html",
        "ggg.html",
    ];

    // @todo this should be a method since we do it all over the place
    foreach ($html_filenames as $name)
    {
        $router->with('/' . $name, function() use ($router) {
            $router->respond('GET', '*', function($request, $response, $service, $app) {
                $response->redirectToURI($request->uri());
            });
        });
    }

    $routes = [
        "500-under-five",
        "family-academy",
        "fresh-garden",
        "ggg",
        "pnrc",
        "ready-to-succeed",
    ];

    foreach ($routes as $route)
    {
        $router->with('/' . $route, function() use ($router) {
            $router->respond('GET', '/', function($request, $response, $service, $app) {
                // Get the template name based on the uri
                $file_path = 'edpsych/research/';
                $template_name = $response->templateNameFromURI($request->uri());
                $template_path = $file_path . $template_name;

                // render the template
                echo $app->template->render($template_path, ["section" => "research"]);
            });
        });
    }

    $router->with('/labs', function() use ($router) {
        $router->respond('GET', '/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/research/labs/', 301)->send();
        });

        $html_filenames = [
            'bart.html',
            'christ.html',
            'codding.html',
            'svarma.html',
            'yussen.html',
            'fleury.html',
            'johnson.html',
            'kvarma.html',
            'maruyama.html',
            'mcconnell.html',
            'McMaster.html',
            'miller.html',
            'kendeou.html',
            'kohli.html',
            'sullivan.html',
            'Symons.html',
            'tennyson.html',
            'Vukovic.html',
            'Wolff.html',
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
            // @todo move this template to labs/index.html
            echo $app->template->render('edpsych/research/labs.html', ["section" => "research"]);
        });

        $routes = [
            'bart',
            'christ',
            'codding',
            'fleury',
            'johnson',
            'kendeou',
            'kohli',
            'kvarma',
            'maruyama',
            'mcconnell',
            'mcmaster',
            'miller',
            'sullivan',
            'svarma',
            'symons',
            'tennyson',
            'vukovic',
            'wolff',
            'yussen',
        ];

        foreach ($routes as $route)
        {
            $router->with('/' . $route, function() use ($router) {
                // @todo make this anonymous callback an object to handle these
                $router->respond('GET', '/', function($request, $response, $service, $app) {
                    // Get the template name based on the uri
                    $file_path = 'edpsych/research/labs/';
                    $template_name = $response->templateNameFromURI($request->uri());
                    $template_path = $file_path . $template_name;

                    // render the template
                    echo $app->template->render($template_path, ["section" => "research"]);
                });
            });
        }
    });

    $router->with('/topics', function() use ($router) {
        /**
         * Set up redirects from old .html urls
         */
        $html_filenames = [
            'achievementgap.html',
            'autism.html',
            'behavioral-social.html',
            'instruction-intervention.html',
            'language-literacy-reading.html',
            'mathematical-thinking.html',
            'neurobehavioral-methods.html',
            'prevention.html',
            'professional-development.html',
            'progress.html',
            'statistical-techniques.html',
            'test-and-measures.html',
        ];

        foreach ($html_filenames as $name)
        {
            $router->with('/' . $name, function() use ($router) {
                $router->respond('GET', '*', function($request, $response, $service, $app) {
                    $response->redirectToURI($request->uri());
                });
            });
        }

        /**
         * Routes
         */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/research/topics/index.html', ["section" => "research"]);
        });

        $topics = [
            'achievementgap',
            'autism',
            'behavioral-social',
            'instruction-intervention',
            'language-literacy-reading',
            'mathematical-thinking',
            'neurobehavioral-methods',
            'prevention',
            'professional-development',
            'progress',
            'statistical-techniques',
            'test-and-measures',
        ];

        foreach ($topics as $topic)
        {
            $router->with('/' .$topic, function() use ($router) {
                $router->respond('GET', '*', function($request, $response, $service, $app) {
                    // Get the template name based on the uri
                    $file_path = 'edpsych/research/topics/';
                    $template_name = $response->templateNameFromURI($request->uri());
                    $template_path = $file_path . $template_name;

                    if (file_exists(BASE_PATH . 'templates/' . $template_path))
                    {
                        // render the template
                        echo $app->template->render($template_path, ["section" => "research"]);
                    }
                });
            });
        }
    });

    $router->respond('GET', '/student-dissertations/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/research/student-dissertations.html', ["section" => "research"]);
    });

   $router->with('/resources', function() use ($router) {
        $html_filenames = [
            "RBS-EC.html",
        ];

        // @todo this should be a method since we do it all over the place
        foreach ($html_filenames as $name)
        {
            $router->with('/' . $name, function() use ($router) {
                $router->respond('GET', '*', function($request, $response, $service, $app) {
                    $response->redirectToURI($request->uri());
                });
            });
        }

        $routes = [
            "rbs-ec",
        ];

        foreach ($routes as $route)
        {
            $router->with('/' . $route, function() use ($router) {
                // @todo make this anonymous callback an object to handle these
                $router->respond('GET', '/', function($request, $response, $service, $app) {
                    // Get the template name based on the uri
                    $file_path = 'edpsych/research/resources/';
                    $template_name = $response->templateNameFromURI($request->uri());
                    $template_path = $file_path . $template_name;

                    // render the template
                    echo $app->template->render($template_path, ["section" => "research"]);
                });
            });
        }
    });
});
