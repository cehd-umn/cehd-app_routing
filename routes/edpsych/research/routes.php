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
                    $redirect = strtolower(rtrim($request->uri(), '.html'));
                    $response->redirect($redirect . '/', 301)->send();
                });
            });
        }

        $router->respond('GET', '/', function($request, $response, $service, $app) {
            // @todo move this template to labs/index.html
            echo $app->template->render('edpsych/research/labs.html', ["section" => "research"]);
        });

        $names = [
            'bart',
            'christ',
            'codding',
            'svarma',
            'yussen',
            'fleury',
            'johnson',
            'kvarma',
            'maruyama',
            'mcconnell',
            'mcmaster',
            'miller',
            'kendeou',
            'kohli',
            'sullivan',
            'symons',
            'tennyson',
            'vukovic',
            'wolff',
        ];

        foreach ($names as $name)
        {
            $router->with('/' .$name, function() use ($router) {
                $router->respond('GET', '*', function($request, $response, $service, $app) {
                    // trim the opening and closing slashes
                    $trimmed = ltrim($request->uri(), '/');
                    $trimmed = rtrim($trimmed, '/');

                    // turn the uri into an array
                    $path_array = explode('/', $trimmed);

                    // get the last element and concatenate the html extension
                    $template_name = strtolower(array_pop($path_array)) . '.html';

                    // render the template
                    echo $app->template->render('edpsych/research/labs/'. $template_name, ["section" => "research"]);
                });
            });
        }
    });

    $router->respond('GET', '/topics/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/research/topics.html', ["section" => "research"]);
    });

    $router->respond('GET', '/student-dissertations/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/research/student-dissertations.html', ["section" => "research"]);
    });
});
