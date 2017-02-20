<?php
/**
 * The Ed Psych site app
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @version 0.1.0
 */

const BASE_PATH = __DIR__ . '/../../';
const BASE_CONFIG_PATH = BASE_PATH . 'cehd/config/app/config.yml';
const EDPSYCH_CONFIG_PATH = BASE_PATH . 'cehd/config/edpsych/config.yml';

require_once BASE_PATH . 'vendor/autoload.php';
require_once BASE_PATH . 'cehd/app/helpers/Navigation.php';
require_once BASE_PATH . 'cehd/app/helpers/Bootstrap.php';
require_once BASE_PATH . 'cehd/app/helpers/Helper.php';
require_once BASE_PATH . 'cehd/app/Response/BaseResponse.php';
require_once BASE_PATH . 'cehd/app/Search/Search.php';
require_once BASE_PATH . 'cehd/app/People/People.php';

/**
 * Instantiate a new instance of Klein. I'm calling it `$router` because I like
 * that better.
 */
$router = new \Klein\Klein();

/**
 * Instantiate a new boostrap helper Class
 * TODO figure out how to only do this once
 */
$bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;

/**
 * Set up a global config to pass into the Klein app
 * TODO There must be a way to do this inside Klein. Perhaps the next minor
 * version will support that.
 */
$global_config = $bootstrap_helper::bootstrap_config(
    [
        BASE_CONFIG_PATH,
        EDPSYCH_CONFIG_PATH,
    ]
);

/**
 * Register Twig and set up some global vars for templates
 * Register a Guzzle HTTP client for making requests to the people tool
 */
$router->respond(function($request, $response, $service, $app) use ($router) {

    /**
     * Register an app site_config using info in YAML files
     */
    $app->register('site_config', function() {
        // TODO This is not DRY
        $bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;
        return $bootstrap_helper::bootstrap_config(
            [
                BASE_CONFIG_PATH,
                EDPSYCH_CONFIG_PATH,
            ]
        );
    });

    /**
     * Register Twig as the template engine for the app
     */
    $app->register('template', function() {
        // TODO This is not DRY
        $bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;

        // Loads environment and other settings from yaml files into a single array
        $site_config = $bootstrap_helper::bootstrap_config(
            [
                BASE_CONFIG_PATH,
                EDPSYCH_CONFIG_PATH,
            ]
        );

        // Set up nav from yaml file
        $nav_file_path = BASE_PATH . 'cehd/config/nav/edpsych/nav.yml';
        $nav_helper = new CEHD\Helpers\Navigation\Navigation();
        $nav_helper->set_nav($nav_file_path);

        // Set up configuration variables for Twig
        $asset_base = $site_config['base_url'] . '/assets';
        $nunjucks_view_base = $asset_base . '/dist/views';
        $css_base = $asset_base . '/dist/css';
        $js_base = $asset_base . '/dist/js';
        $cehd_edpsych_nav_open = false;
        $twig = $bootstrap_helper::bootstrap_twig($site_config['debug']);
        $twig_config = [
            'asset_base' => $asset_base,
            'base_url' => $site_config['base_url'],
            'copyright_year' => date('Y'),
            'css_base' => $css_base,
            'dept_name' => $site_config['dept_name'],
            'js_base' => $js_base,
            'nunjucks_view_base' => $nunjucks_view_base,
            'nav_items' => $nav_helper->get_nav(),
            'site_config' => $site_config,
        ];

        // Check for main nav state
        if (array_key_exists('cehd-edpsych-navOpen', $_COOKIE)) {
            $cehd_edpsych_nav_open = $_COOKIE['cehd-edpsych-navOpen'];
        }

        // Add global variables to Twig
        $twig->addGlobal('config', $twig_config);
        $twig->addGlobal('cehd_edpsych_nav_open', $cehd_edpsych_nav_open);
        return $twig;
    });

    /**
     * Register a GuzzleHTTP client for the app. This is used to make requests
     * for things like news and People Tool feeds.
     */
    $app->register('http_client', function() {
        // TODO This is not DRY
        $bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;
        $http_client = $bootstrap_helper::bootstrap_http_client([]);
        return $http_client;
    });

    // TODO The helper functions we need in the app here should go in the
    // Helper class below
    $app->register('bootstrap_helper', function() {
        $bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;
        return $bootstrap_helper;
    });

    $app->register('helpers', function() {
        $helpers = new \CEHD\Helpers\Helper\Helper;
        return $helpers;
    });

    /**
     * Register the Search class to handle Google Search Appliance requests
     */
    $app->register('search_client', function() {
        return new \CEHD\App\Search\Search();
    });
});

$router->with("/EdPsych", function() use ($router)
{
    $router->respond('GET', '*', function($request, $response, $service, $app) {
        $new_uri = substr_replace($request->uri(), "edpsych", 1, 7);
		$response->redirect($new_uri, 301)->send();
    });
});

/**
 * Set up routes and redirects
 */
$router->with($global_config['base_url'], function() use ($router) {
    /**
     * Add a trailing slash if it's missing except for /services requests or
     * .html uris which should either 404 or have a redirect set up
     */
    $router->respond('GET', '*', function($request, $response, $service, $app) {
        $uri_components = explode("?", $request->uri());

        $uri = $uri_components[0];

        if (count($uri_components) > 1)
        {
            $query_str = $uri_components[1];
        }
        else
        {
            $query_str = "";
        }

        if (!strpos($uri, "services"))
        {
            // grab the last character in the uri to see if it's a slash
            $last = substr($uri, -1);

            // grab the last 4 characters to see if it's "html". Anything ending in
            // .html is likely to need a specific redirect
            $html = substr($uri, -4);

            if ($last !== "/" && $html !== "html")
            {
                $with_slash = $uri . "/";
                $redirect_uri = $uri . "/" . $query_str;
                $response->redirect($redirect_uri, 301)->send();
            }
        }
    });

    /**
     * Ed Psych Home
     */
    $router->respond('GET', '/default.html', function($request, $response, $service, $app) {
        $response->redirect($app->site_config['base_url'] . '/', 301)->send();
    });

    $router->respond('GET', '/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/index.html', []);
    });

    /**
     * Examples
     * Only loads if $_SERVER['SERVER_NAME'] contains "scotch" or "drydock"
     */
    $router->with('/examples', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            // Only expose this route if not in production
            if (!$app->site_config['is_prod'])
            {
                echo $app->template->render('edpsych/examples/index.html', []);
            }
        });

        $router->respond('GET', '/macros/', function($request, $response, $service, $app) {
            // Only expose this route if not in production
            if (!$app->site_config['is_prod'])
            {
                echo $app->template->render('edpsych/examples/macros.html', []);
            }
        });
    });

    $router->with('/contact.html', function() use ($router) {
        $router->respond('GET', '*', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/contact/', 301)->send();
        });
    });

    $router->with('/contact-confirmation.html', function() use ($router) {
        $router->respond('GET', '*', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/contact/confirmation/', 301)->send();
        });
    });

    $router->with('/contact', function() use ($router) {
        /**
         * Routes
         */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/contact/index.html', []);
        });

        $router->respond('GET', '/confirmation/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/contact/confirmation.html', []);
        });
    });

    $router->respond('GET', '/search/', function($request, $response, $service, $app) {
        $query_data = $request->params();
        $results = $app->search_client->get($query_data);
        $template_data = [
            "results" => $results,
            "query" => $query_data["q"],
        ];

        echo $app->template->render('edpsych/search.html', $template_data);
    });

    /**
     * About
     */
    $router->with('/About', function() use ($router) {
        $router->respond('GET', '*', function($request, $response, $service, $app) {
            $response->redirectToLowercaseURI($request->uri());
        });
    });

    $router->with('/about', function() use ($router) {
        /**
         * Redirects
         * TODO Figure out a more programmatic way to manage these redirects
         */
        $router->respond('GET', '/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/about/', 301)->send();
        });

        $router->respond('GET', '/diversity-inclusion.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/about/diversity-inclusion/', 301)->send();
        });

        /**
         * Routes
         */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/about/index.html', ["section" => 'about']);
        });

        $router->respond('GET', '/diversity-inclusion/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/about/diversity.html', ["section" => 'about']);
        });
    });

    /**
     * Alumni
     */
    $router->with('/Alumni', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            $response->redirectToLowercaseURI($request->uri());
        });
    });

    $router->with('/alumni', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/alumni/index.html', []);
        });

        $router->respond('GET', '/information/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/alumni/information.html', []);
        });
    });

    /*
     * Future Students
     */
    $router->with('/FutureStudents', function() use ($router) {
        /**
         * Redirects
         */
        $router->respond('GET', '/CSPP.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/future-students/cspp/', 301)->send();
        });

        $router->respond('GET', '/Foundations.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/future-students/foundations/', 301)->send();
        });

        $router->respond('GET', '/QME.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/future-students/qme/', 301)->send();
        });

        $router->respond('GET', '/schoolpsych.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/future-students/school-psych/', 301)->send();
        });

        $router->respond('GET', '/SpecialEd.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/future-students/special-ed/', 301)->send();
        });

        $router->respond('GET', '/SpEd-MEd.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/future-students/special-ed-med/', 301)->send();
        });

        $router->respond('GET', '/pridefellow.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/pride-fellow/', 301)->send();
        });

        $router->respond('GET', '/master.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/master/', 301)->send();
        });

        $router->respond('GET', '/doctor.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/doctor/', 301)->send();
        });

    });

    $router->with('/future-students', function() use ($router) {
        /**
         * Routes
         */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/future-students/index.html', []);
        });

        $router->respond('GET', '/cspp/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/future-students/cspp.html', []);
        });

        $router->respond('GET', '/foundations/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/future-students/foundations.html', []);
        });

        $router->respond('GET', '/qme/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/future-students/qme.html', []);
        });

        $router->respond('GET', '/school-psych/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/future-students/school-psych.html', []);
        });

        $router->respond('GET', '/special-ed/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/future-students/special-ed.html', []);
        });

        $router->respond('GET', '/special-ed-med/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/future-students/special-ed-med.html', []);
        });
    });

    /**
     * Current Students
     */
    require_once BASE_PATH . 'routes/edpsych/current/redirects.php';
    require_once BASE_PATH . 'routes/edpsych/current/routes.php';

    /**
     * Giving
     */
    $router->with('/forms', function() use ($router) {
        /**
         * Redirects
         */
        $router->respond('GET', '/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/', 301)->send();
        });

        $router->respond('GET', '/giving.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/giving/', 301)->send();
        });

        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/forms/index.html', []);
        });

    });

    $router->with('/giving', function() use ($router) {

        /**
         * Routes
         */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/giving/index.html', []);
        });
    });

    /**
     * Services
     * TODO Make these services their own thing
     * TODO return 404's if query params are not valid
     */
    $router->with('/services', function() use ($router) {
        /**
         * Search
         */
        $router->with('/search', function() use ($router) {
            $router->respond('GET', '/', function($request, $response, $service, $app) {
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

        $router->with('/people-tool', function() use ($router) {
            /**
             * GET handler for directory
             */
            $router->respond('GET', '/', function($request, $response, $service, $app) {
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
            $router->respond('GET', '/person/', function($request, $response, $service, $app) {
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
        $router->with('/news', function() use ($router) {
            $router->respond('GET', '/feed/', function($request, $response, $service, $app) {
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
        $router->with('/events', function() use ($router) {
            $router->respond('GET', '/feed/', function($request, $response, $service, $app) {
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

        $router->with('/programs', function() use ($router) {
            $router->respond('GET', '/', function($request, $response, $service, $app) {
                // $base_url =  'https://apps.cehd.umn.edu/Programs/RSSFeed.aspx?t=&p=EPSY_ASL&d=both&a=both';
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
    $router->with('/people', function() use ($router) {
        $router->respond('GET', '/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/people/', 301)->send();
        });

        $router->respond('GET', '/', function($request, $response, $service, $app) {
            $yaml_file_path = BASE_PATH . 'cehd/config/edpsych/people/index.yml';
            $section_data = $app->bootstrap_helper->load_yaml($yaml_file_path);

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

        $router->respond('GET', '/[:internetId]/', function($request, $response, $service, $app) {
            $yaml_file_path = BASE_PATH . 'cehd/config/edpsych/people/extra_content.yml';
            $yaml_data = $app->bootstrap_helper->load_yaml($yaml_file_path);
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
        $router->with('/Faculty', function() use ($router) {
            // @todo turn this into a method that can be reused
            $router->respond('GET', '*', function($request, $response, $service, $app) {
                $yaml_file_path = BASE_PATH . 'cehd/config/edpsych/people/redirects.yml';
                $redirect_data = $app->bootstrap_helper->load_yaml($yaml_file_path);
                $redirects = new \UMN\CEHD\Dict\Dict($redirect_data["faculty"]);
                $uri = $request->uri();
                $file_name = $app->helpers->getLastFromPath($app->helpers->stripDotHtml($uri));
                $new_path = $redirects->get($file_name);
                if ($new_path)
                {
                    $response->redirect($app->site_config['base_url'] . '/people/' . $new_path . '/', 301)->send();
                }
            });
        });

        /**
         * Staff redirects
         */
        $router->with('/Staff', function() use ($router) {
            /**
             * Redirects
             */
            $router->respond('GET', '*', function($request, $response, $service, $app) {
                $yaml_file_path = BASE_PATH . 'cehd/config/edpsych/people/redirects.yml';
                $redirect_data = $app->bootstrap_helper->load_yaml($yaml_file_path);
                $redirects = new \UMN\CEHD\Dict\Dict($redirect_data["staff"]);
                $uri = $request->uri();
                $file_name = $app->helpers->getLastFromPath($app->helpers->stripDotHtml($uri));
                $new_path = $redirects->get($file_name);
                if ($new_path)
                {
                    $response->redirect($app->site_config['base_url'] . '/people/' . $new_path . '/', 301)->send();
                }
            });
        });
    });

    /**
     * Programs
     */
    require_once BASE_PATH . 'routes/edpsych/programs/redirects.php';
    require_once BASE_PATH . 'routes/edpsych/programs/routes.php';

    /**
     * Research
     */
    require_once BASE_PATH . 'routes/edpsych/research/routes.php';


    $router->respond('404', function ($request) {
        $page = $request->uri();
        echo "Oops, it looks like $page doesn't exist..\n";
    });
});

$router->dispatch(null, new \CEHD\App\Response\BaseResponse());
