<?php
/**
 * The Ed Psych site app
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @version 0.1.0
 */

// @todo verify that pages all work
// @todo bring sanity to the redirects and check them.

require_once __DIR__ . "/../../routes/edpsych/static_routes.php";
require_once __DIR__ . '/../../cehd/app/Response/BaseResponse.php';
require_once __DIR__ . '/../../cehd/app/People/People.php';
// @todo patch up these redirects or rewrite them
// require_once __DIR__ . '/../../routes/edpsych/current/redirects.php';
// require_once BASE_PATH . 'routes/edpsych/programs/redirects.php';

$app->router->with("/EdPsych", function() use ($app)
{
    $app->router->respond('GET', '*', function($request, $response, $service, $app) {
        $new_uri = substr_replace($request->uri(), "edpsych", 1, 7);
        $response->redirect($new_uri, 301)->send();
    });
});

$app->router->respond('GET', '/edpsych/default.html', function($request, $response, $service, $app) {
    $response->redirect('/edpsych/', 301)->send();
});

$app->router->respond('GET', '/edpsych/examples/', function($request, $response, $service, $app) {
    // Only expose this route if not in production
    if (!$app->config['is_prod'])
    {
        echo $app->template->render('edpsych/examples/index.html', []);
    }
});

$app->router->respond('GET', '/edpsych/examples/macros/', function($request, $response, $service, $app) {
    // Only expose this route if not in production
    if (!$app->config['is_prod'])
    {
        echo $app->template->render('edpsych/examples/macros.html', []);
    }
});

$app->router->respond('GET', '/edpsych/contact.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/contact/', 301)->send();
});

$app->router->respond('GET', '/edpsych/contact-confirmation.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/contact/confirmation/', 301)->send();
});

$app->router->respond('GET', '/edpsych/search/', function($request, $response, $service, $app) {
    $query_data = $request->params();
    $results = $app->search_client->get($query_data);
    $template_data = [
        "results" => $results,
        "query" => $query_data["q"],
    ];

    echo $app->template->render('edpsych/search.html', $template_data);
});

$app->router->respond('GET', '/edpsych/About/', function($request, $response, $service, $app) {
    $response->redirectToLowercaseURI($request->uri());
});

$app->router->respond('GET', '/edpsych/about/default.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/about/', 301)->send();
});

$app->router->respond('GET', '/edpsych/about/diversity-inclusion.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/about/diversity-inclusion/', 301)->send();
});

$app->router->respond('GET', '/edpsych/Alumni/', function($request, $response, $service, $app) {
    $response->redirectToLowercaseURI($request->uri());
});

$app->router->respond('GET', '/edpsych/FutureStudents/CSPP.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/future-students/cspp/', 301)->send();
});

$app->router->respond('GET', '/edpsych/FutureStudents/Foundations.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/future-students/foundations/', 301)->send();
});

$app->router->respond('GET', '/edpsych/FutureStudents/QME.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/future-students/qme/', 301)->send();
});

$app->router->respond('GET', '/edpsych/FutureStudents/schoolpsych.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/future-students/school-psych/', 301)->send();
});

$app->router->respond('GET', '/edpsych/FutureStudents/SpecialEd.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/future-students/special-ed/', 301)->send();
});

$app->router->respond('GET', '/edpsych/FutureStudents/SpEd-MEd.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/future-students/special-ed-med/', 301)->send();
});

$app->router->respond('GET', '/edpsych/FutureStudents/pridefellow.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/programs/schoolpsych/pride-fellow/', 301)->send();
});

$app->router->respond('GET', '/edpsych/FutureStudents/master.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/programs/schoolpsych/master/', 301)->send();
});

$app->router->respond('GET', '/edpsych/FutureStudents/doctor.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/programs/schoolpsych/doctor/', 301)->send();
});


$app->router->respond('GET', '/edpsych/forms/default.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/forms/', 301)->send();
});

$app->router->respond('GET', '/edpsych/giving.html', function($request, $response, $service, $app) {
    $response->redirect($app->config['base_url'] . '/giving/', 301)->send();
});

/**
    * Services
    * TODO Make these services their own thing
    * TODO return 404's if query params are not valid
    */
$app->router->with('/edpsych/services', function() use ($app) {
    /**
        * Search
        */
    $app->router->with('/search', function() use ($app) {
        $app->router->respond('GET', '/', function($request, $response, $service, $app) {
            $query_data = $request->params();
            $results = $app->search_client->get($query_data);
            $response->sendJSONResponse(json_encode($results));
        });
    });

    /**
     * People Tool
     *
     * This makes a request for People Tool data, parses it to JSON and
     * sends a JSON blob back
     */
    $app->router->with('/people-tool', function() use ($app) {
        /**
            * GET handler for directory
            */
        $app->router->respond('GET', '/', function($request, $response, $service, $app) {
            // TODO add base urls to config
            $base_url =  'https://apps.cehd.umn.edu/people/admin/RSSFeed.aspx';
            $query_data = $request->params();
            $query = [];

            // Strip out extraneous query params
            if (array_key_exists('en', $query_data))
            {
                $query['en'] = $query_data['en'];
            };

            if (array_key_exists('tg', $query_data))
            {
                $query['tg'] = $query_data['tg'];
            };

            if (array_key_exists('en', $query_data) || array_key_exists('tg', $query_data))
            {
                $client_query_data = ['query' => $query];
                $client_response = $app->http_client->request('GET', $base_url, $client_query_data);
                $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
            }
        });

        /**
         * GET handler for individual people
         */
        $app->router->respond('GET', '/person/', function($request, $response, $service, $app) {
            $query_data = $request->params();
            $base_url =  'https://apps.cehd.umn.edu/people/admin/XMLFeed.aspx';

            if (array_key_exists('internetId', $query_data)) {
                $client_query_data = ['query' => [
                    'id' => $query_data['internetId'
                    ]
                ]];
                $client_response = $app->http_client->request('GET', $base_url, $client_query_data);
                $person = new \CEHD\App\People\Person($client_response->getBody());
                $response->sendJSONResponse($person->toJSON());
            }
        });
    });

    /**
     * News Feeds
     *
     * This makes a request to the news RSS feed, parses it to JSON and
     * sends a JSON blob back
     */
    $app->router->with('/news', function() use ($app) {
        $app->router->respond('GET', '/feed/', function($request, $response, $service, $app) {
            $base_url = "http://news.cehd.umn.edu/";
            $query_data = $request->params();
            if (array_key_exists('feed-path', $query_data)) {
                $request_url = $base_url . $query_data['feed-path'];
                $client_response = $app->http_client->request('GET', $request_url);
                $xml = $client_response->getBody();
                $xml_obj = $response->xmlStrToObject($xml);
                $result_array = $response->getNewsfeedArrayFromXML($xml_obj);
                $json = json_encode($result_array);
                $response->sendJSONResponse($json);
            }
        });
    });

    /**
     * Events Calendar
     *
     * This makes a request to the news RSS feed, parses it to JSON and
     * sends a JSON blob back
     */
    $app->router->with('/events', function() use ($app) {
        $app->router->respond('GET', '/feed/', function($request, $response, $service, $app) {
            $base_url =  'https://apps.cehd.umn.edu/events/Admin/XMLFeed.aspx';
            $query_data = $request->params();
            if (array_key_exists('r', $query_data) && array_key_exists('t', $query_data) && array_key_exists('q', $query_data)) {
                $client_query_data = ['query' => [
                    'r' => $query_data['r'],
                    't' => $query_data['t'],
                    'q' => $query_data['q'],
                    ]
                ];
                $client_response = $app->http_client->request('GET',$base_url, $client_query_data);
                $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
            }
        });
    });

    $app->router->with('/programs', function() use ($app) {
        $app->router->respond('GET', '/', function($request, $response, $service, $app) {
            $base_url =  'https://apps.cehd.umn.edu/Programs/RSSFeed.aspx';
            $query_data = $request->params();
            $client_query_data = ['query' => []];
            $valid_query_params = [
                'a',
                'd',
                'p',
                't',
            ];

            foreach ($valid_query_params as $valid_query_param)
            {
                if (array_key_exists($valid_query_param, $query_data))
                {
                    $client_query_data['query'][$valid_query_param] = $query_data[$valid_query_param];
                }
            }

            if (!empty($client_query_data))
            {
                $client_response = $app->http_client->request('GET', $base_url, $client_query_data);
                $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
            }
        });
    });
});

/**
 * People
 */
$app->router->with('/edpsych/people', function() use ($app) {
    $app->router->respond('GET', '/default.html', function($request, $response, $service, $app) {
        $response->redirect($app->config['base_url'] . '/people/', 301)->send();
    });

    $app->router->respond('GET', '/', function($request, $response, $service, $app) {
        $yaml_file_path = __DIR__ . '/../../cehd/config/edpsych/people/index.yml';
        $section_data = $app->bootstrap_helpers->load_yaml($yaml_file_path);

        foreach ($section_data as $key => $section)
        {
            $section_data[$key]["section_hyphenated"] = $app->helpers->hyphenate($section_data[$key]["section"]);
        };

        $template_vars = [
            "section" => "people",
            "section_data" => $section_data,
        ];
        echo $app->template->render('edpsych/people/index.html', $template_vars);
    });

    $app->router->respond('GET', '/[:internetId]/', function($request, $response, $service, $app) {
        $yaml_file_path = __DIR__ . '/../../cehd/config/edpsych/people/extra_content.yml';
        $yaml_data = $app->bootstrap_helpers->load_yaml($yaml_file_path);
        $extra_content_data = new \UMN\CEHD\Dict\Dict($yaml_data);
        $extra_content = $extra_content_data->get($request->internetId);

        $template_vars = [
            "section" => "people",
            "internet_id" => $request->internetId,
            "extra_content" => $extra_content,
        ];
        echo $app->template->render('edpsych/people/person.html', $template_vars);
    });


    /**
     * Faculty redirects
     */

    // @todo turn this into a method that can be reused
    function faculty_redirect($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . '/../../cehd/config/edpsych/people/redirects.yml';
        $redirect_data = $app->bootstrap_helpers->load_yaml($yaml_file_path);
        $redirects = new \UMN\CEHD\Dict\Dict($redirect_data["faculty"]);
        $uri = $request->uri();
        $file_name = ucfirst($app->helpers->getLastFromPath($app->helpers->stripDotHtml($uri)));
        $new_path = $redirects->get($file_name);
        if ($new_path)
        {
            $response->redirect($app->config['base_url'] . '/people/' . $new_path . '/', 301)->send();
        }
    };

    $app->router->with('/Faculty', function() use ($app) {
        $app->router->respond('GET', '*', function($request, $response, $service, $app) {
            faculty_redirect($request, $response, $service, $app);
        });
    });

    $app->router->with('/faculty', function() use ($app) {
        $app->router->respond('GET', '*', function($request, $response, $service, $app) {
            faculty_redirect($request, $response, $service, $app);
        });
    });

    /**
     * Staff redirects
     */
    $app->router->with('/Staff', function() use ($app) {
        /**
         * Redirects
         */
        $app->router->respond('GET', '*', function($request, $response, $service, $app) {
            $yaml_file_path = __DIR__ . '/../../cehd/config/edpsych/people/redirects.yml';
            $redirect_data = $app->bootstrap_helpers->load_yaml($yaml_file_path);
            $redirects = new \UMN\CEHD\Dict\Dict($redirect_data["staff"]);
            $uri = $request->uri();
            $file_name = $app->helpers->getLastFromPath($app->helpers->stripDotHtml($uri));
            $new_path = $redirects->get($file_name);
            if ($new_path)
            {
                $response->redirect($app->config['base_url'] . '/people/' . $new_path . '/', 301)->send();
            }
        });
    });
});
$app->router->dispatch(null, new \CEHD\App\Response\BaseResponse());
