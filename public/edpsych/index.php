<?php
const BASE_PATH = __DIR__ . '/../../';

require_once BASE_PATH . 'vendor/autoload.php';
require_once BASE_PATH . 'cehd/apps/helpers/Navigation.php';
require_once BASE_PATH . 'cehd/apps/helpers/Bootstrap.php';
require_once BASE_PATH . 'cehd/apps/Response/BaseResponse.php';

// TODO Re-evaluate how this config is managed
class Config {
    const BASE_URL = '/edpsych-twig';
    const DEBUG = true;
    const DEPT_NAME = "Department of Educational Psychology";
}

$router = new \Klein\Klein();

/**
 * Register Twig and set up some global vars for templates
 * Register a Guzzle HTTP client for making requests to the people tool
 */
$router->respond(function($request, $response, $service, $app) use ($router) {
    $app->register('template', function() {

        // Set up nav from yaml file
        $nav_file_path = BASE_PATH . 'cehd/config/nav/edpsych/nav.yml';
        $nav_helper = new CEHD\Helpers\Navigation\Navigation();
        $nav_helper->set_nav($nav_file_path);

        $asset_base = Config::BASE_URL . '/assets';
        $nunjucks_view_base = $asset_base . '/dist/views';
        $css_base = $asset_base . '/dist/css';
        $js_base = $asset_base . '/dist/js';
        $cehd_edpsych_nav_open = false;
        $bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;
        $twig = $bootstrap_helper::bootstrap_twig(Config::DEBUG);
        $config = [
            'asset_base' => $asset_base,
            'base_url' => Config::BASE_URL,
            'copyright_year' => date('Y'),
            'css_base' => $css_base,
            'dept_name' => Config::DEPT_NAME,
            'js_base' => $js_base,
            'nunjucks_view_base' => $nunjucks_view_base,
            'nav_items' => $nav_helper->get_nav(),
        ];

        // Check for main nav state
        if (array_key_exists('cehd-edpsych-navOpen', $_COOKIE)) {
            $cehd_edpsych_nav_open = $_COOKIE['cehd-edpsych-navOpen'];
        }

        $twig->addGlobal('config', $config);
        $twig->addGlobal('cehd_edpsych_nav_open', $cehd_edpsych_nav_open);
        return $twig;
    });

    $app->register('http_client', function() {
        $client_params = [
            'base_uri' => "https://apps.cehd.umn.edu/people/admin/",
        ];
        $bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;
        $http_client = $bootstrap_helper::bootstrap_http_client([]);
        // $http_client->setDefaultOption('verify', false);
        return $http_client;
    });
});

/**
 * Set up routes and redirects
 */
$router->with(Config::BASE_URL, function() use ($router) {

    /**
     * Ed Psych Home
     */
    $router->respond('GET', '/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/index.html', []);
    });

    /**
     * About
     */
    $router->with('/about', function() use ($router) {
        /**
         * Redirects
         * TODO Figure out a more programmatic way to manage these redirects
         */
        $router->respond('GET', '/diversity-inclusion.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/about/diversity-inclusion/', 301)->send();
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
    $router->with('/alumni', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/alumni/index.html', []);
        });
    });

    /*
     * Future Students
     */
    $router->with('/future-students', function() use ($router) {
        /**
         * Redirects
         */
        $router->respond('GET', '/CSPP.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/future-students/cspp/', 301)->send();
        });

        $router->respond('GET', '/Foundations.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/future-students/foundations/', 301)->send();
        });

        $router->respond('GET', '/QME.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/future-students/qme/', 301)->send();
        });

        $router->respond('GET', '/schoolpsych.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/future-students/school-psych/', 301)->send();
        });

        $router->respond('GET', '/SpecialEd.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/future-students/special-ed/', 301)->send();
        });

        $router->respond('GET', '/SpEd-MEd.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/future-students/special-ed-med/', 301)->send();
        });

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

    /*
     * Current Students
     */
    $router->with('/current', function() use ($router) {
        /**
         * Redirects
         */
        $router->respond('GET', '/CSPP.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/current/cspp/', 301)->send();
        });

        $router->respond('GET', '/Foundations.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/current/foundations/', 301)->send();
        });

        $router->respond('GET', '/QME.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/current/qme/', 301)->send();
        });

        $router->respond('GET', '/SpecialEd.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/current/special-ed/', 301)->send();
        });

        /**
         * Routes
         */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/index.html', ["section" => "current"]);
        });

        $router->respond('GET', '/cspp/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/cspp.html', ["section" => "current"]);
        });

        $router->respond('GET', '/foundations/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/foundations.html', ["section" => "current"]);
        });

        $router->respond('GET', '/qme/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/qme.html', ["section" => "current"]);
        });

        $router->respond('GET', '/special-ed/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/special-ed.html', ["section" => "current"]);
        });
    });

    /**
     * Giving
     */
    $router->with('/forms', function() use ($router) {
        /**
         * Redirects
         */
        $router->respond('GET', '/giving.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/giving/', 301)->send();
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
                // TODO make the query string a var so we can have one endpoint
                $client_query_data = ['query' => ['tg' => '213']];
                $client_response = $app->http_client->request('GET', $base_url, $client_query_data);
                $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
            });

            /**
             * GET handler for individual people
             */
            $router->respond('GET', '/person/', function($request, $response, $service, $app) {
                $query_data = $request->params();
                $base_url =  'https://apps.cehd.umn.edu/people/admin/RSSFeed.aspx';

                if (array_key_exists('internetId', $query_data)) {
                    $client_query_data = ['query' => [
                        'id' => $query_data['internetId'
                        ]
                    ]];
                    $client_response = $app->http_client->request('GET', $base_url, $client_query_data);
                    $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
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
                    $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
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
    });

    /**
     * People
     */
    $router->with('/people', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/people/index.html', ["section" => "people"]);
        });

        $router->respond('GET', '/[:internetId]/', function($request, $response, $service, $app) {
            $template_vars = [
                "section" => "people",
                "internet_id" => $request->internetId,
            ];
            echo $app->template->render('edpsych/people/person.html', $template_vars);
        });


        /**
         * Faculty
         * TODO Set up redirects once directory is set up
         */
        $router->with('/faculty', function() use ($router) {
            /**
             * Redirects
             */
            $router->respond('GET', '/Penny.html', function($request, $response, $service, $app) {
                // TODO fix this redirect
                $response->redirect(Config::BASE_URL . '/people/faculty/penny/', 301)->send();
            });

            /**
             * Routes
             */
            // $router->respond('GET', '/penny/', function($request, $response, $service, $app) {
            //     echo $app->template->render('edpsych/people/faculty/penny.html', ["section" => "people"]);
            // });
        });

        /**
         * Staff
         */
        $router->with('/staff', function() use ($router) {
            /**
             * Redirects
             */
            // $router->respond('GET', '/Penny.html', function($request, $response, $service, $app) {
            //     $response->redirect(Config::BASE_URL . '/people/faculty/penny/', 301)->send();
            // });

            /**
             * Routes
             */
        });
    });

    // PROGRAMS AND DEGREES
    $router->with('/programs', function() use ($router) {
        /**
         * Redirects
         */
        $router->respond('GET', '/CSPP/default.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/programs/cspp/', 301)->send();
        });

        $router->respond('GET', '/QME/default.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/programs/qme/', 301)->send();
        });

        $router->respond('GET', '/Graduate-Minor.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/programs/graduate-minor/', 301)->send();
        });

        $router->respond('GET', '/SchoolPsych/students.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/programs/schoolpsych/students/', 301)->send();
        });

        /**
         * Routes
         */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/index.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/asl/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/asl.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/cspp/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/cspp.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/foundations/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/foundations.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/qme/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/qme.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/schoolpsych/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/schoolpsych/students/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/schoolpsych-students.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/specialed/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/specialed.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/graduate-minor/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/graduate-minor.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/minor/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/minor.html', ["section" => "programs-degrees"]);
        });
    });

    /**
     * Research
     */
    $router->with('/research', function() use ($router) {
        /**
         * Redirects
         */
        $router->respond('GET', '/default.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/research/', 301)->send();
        });

        $router->respond('GET', '/labs/default.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/research/labs/', 301)->send();
        });

        $router->respond('GET', '/topics/default.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/research/topics/', 301)->send();
        });

        $router->respond('GET', '/student-dissertations/default.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/research/student-dissertations/', 301)->send();
        });

        /**
         * Routes
         */
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/research/index.html', ["section" => "research"]);
        });

        $router->respond('GET', '/labs/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/research/labs.html', ["section" => "research"]);
        });

        $router->respond('GET', '/topics/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/research/topics.html', ["section" => "research"]);
        });

        $router->respond('GET', '/student-dissertations/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/research/student-dissertations.html', ["section" => "research"]);
        });
    });
});

$router->dispatch(null, new \CEHD\Apps\Response\BaseResponse());
