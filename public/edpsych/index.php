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
require_once BASE_PATH . 'cehd/app/Response/BaseResponse.php';
require_once BASE_PATH . 'cehd/app/Config/Config.php';

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
});

/**
 * Set up routes and redirects
 */
$router->with($global_config['base_url'], function() use ($router) {
    /**
     * Ed Psych Home
     */
    $router->respond('GET', '/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/index.html', []);
    });

    /**
     * Examples
     * Only loads if $_SERVER['SERVER_NAME'] contains "scotch" or "drydock"
     */
    $router->respond('GET', '/examples/', function($request, $response, $service, $app) {
        // Only expose this route if not in production
        if (!$app->site_config['is_prod'])
        {
            echo $app->template->render('edpsych/examples/index.html', []);
        }
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
    $router->with('/future-students', function() use ($router) {
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

        $router->respond('GET', '/cspp/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/cspp.html', ["section" => "current"]);
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

        $router->respond('GET', '/qme/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/qme.html', ["section" => "current"]);
        });

        $router->respond('GET', '/special-ed/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/special-ed.html', ["section" => "current"]);
        });

        $router->respond('GET', '/student-teaching-practicum/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/current/student-teaching-practicum.html', ["section" => "current"]);
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

    /**
     * Giving
     */
    $router->with('/forms', function() use ($router) {
        /**
         * Redirects
         */
        $router->respond('GET', '/giving.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/giving/', 301)->send();
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
                $response->redirect($app->site_config['base_url'] . '/people/faculty/penny/', 301)->send();
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
            //     $response->redirect($app->site_config['base_url'] . '/people/faculty/penny/', 301)->send();
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
        $router->respond('GET', '/ASL/faq.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/asl/faq/', 301)->send();
        });

        $router->respond('GET', '/ASL/ASL-Placement-Assessment.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/asl/placement-assessment/', 301)->send();
        });

        $router->respond('GET', '/Courses/ASL.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/courses/asl/', 301)->send();
        });

        $router->respond('GET', '/CSPP/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/cspp/', 301)->send();
        });

        $router->respond('GET', '/QME/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/qme/', 301)->send();
        });

        $router->respond('GET', '/Graduate-Minor.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/graduate-minor/', 301)->send();
        });

        $router->respond('GET', '/SchoolPsych/students.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/programs/schoolpsych/students/', 301)->send();
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

        $router->respond('GET', '/asl/faq/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/asl-faq.html', ["section" => "programs-degrees"]);
        });

        $router->respond('GET', '/asl/placement-assessment/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/asl-placement-assessment.html', ["section" => "programs-degrees"]);
        });

        // @TODO Get something working for the programs RSS feed
        $router->respond('GET', '/courses/asl/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/programs/courses/asl.html', ["section" => "programs-degrees"]);
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

            $router->respond('GET', '/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/programs/cspp/index.html', ["section" => "programs-degrees"]);
            });

            $router->respond('GET', '/hansen/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/programs/cspp/hansen.html', ["section" => "programs-degrees"]);
            });

            $router->respond('GET', '/sullivan/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/programs/cspp/sullivan.html', ["section" => "programs-degrees"]);
            });

            $router->with('/handbooks', function() use ($router) {
                /**
                 * Redirects
                 */
                $router->respond('GET', '/AdvancedPracticum/default.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/advanced-practicum/', 301)->send();
                });

                $router->respond('GET', '/AdvancedPracticum/', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/advanced-practicum/', 301)->send();
                });

                $router->respond('GET', '/AdvancedPracticum/Expectations.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/advanced-practicum/expectations/', 301)->send();
                });

                $router->respond('GET', '/AdvancedPracticum/advanced.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/advanced-practicum/advanced/', 301)->send();
                });

                $router->respond('GET', '/AdvancedPracticum/documentation.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/advanced-practicum/documentation/', 301)->send();
                });

                $router->respond('GET', '/AdvancedPracticum/interview.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/advanced-practicum/interview/', 301)->send();
                });

                $router->respond('GET', '/AdvancedPracticum/liability.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/advanced-practicum/liability/', 301)->send();
                });

                $router->respond('GET', '/Counseling/default.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/', 301)->send();
                });

                $router->respond('GET', '/Counseling/CEU.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/ceu/', 301)->send();
                });

                $router->respond('GET', '/Counseling/Guidelines.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/guidelines/', 301)->send();
                });

                $router->respond('GET', '/Counseling/K-12.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/k-12/', 301)->send();
                });

                $router->respond('GET', '/Counseling/Minneapolis.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/minneapolis/', 301)->send();
                });

                $router->respond('GET', '/Counseling/areas.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/areas/', 301)->send();
                });

                $router->respond('GET', '/Counseling/forms.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/forms/', 301)->send();
                });

                $router->respond('GET', '/Counseling/information.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/information/', 301)->send();
                });

                $router->respond('GET', '/Counseling/interview.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/interview/', 301)->send();
                });

                $router->respond('GET', '/Counseling/letter.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/letter/', 301)->send();
                });

                $router->respond('GET', '/Counseling/requirements.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/requirements/', 301)->send();
                });

                $router->respond('GET', '/Counseling/site.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/site/', 301)->send();
                });

                $router->respond('GET', '/Counseling/taping.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/counseling/taping/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/default.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/8452.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/8452/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/8509.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/8509/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/8994.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/8994/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/A.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/appendix-a/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/B.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/appendix-b/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/D.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/appendix-d/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/E.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/appendix-e/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/FinalOral.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/final-oral/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/Liability.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/liability/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/Licensing.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/licensing/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/certification.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/certification/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/disclosure.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/disclosure/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/general.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/general/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/graduation.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/graduation/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/internship.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/internship/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/personnel.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/personnel/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/practicum.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/practicum/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/preliminary.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/preliminary/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/procedures.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/procedures/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/seminar.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/seminar/', 301)->send();
                });

                $router->respond('GET', '/Doctoral/thesis.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/doctoral/thesis/', 301)->send();
                });

                $router->respond('GET', '/Masters/', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/thesis/', 301)->send();
                });

                $router->respond('GET', '/Masters/default.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/thesis/', 301)->send();
                });

                $router->respond('GET', '/Masters/A.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/appendix-a/', 301)->send();
                });

                $router->respond('GET', '/Masters/C.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/appendix-c/', 301)->send();
                });

                $router->respond('GET', '/Masters/D.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/appendix-d/', 301)->send();
                });

                $router->respond('GET', '/Masters/L.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/appendix-l/', 301)->send();
                });

                $router->respond('GET', '/Masters/Commencement.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/commencement/', 301)->send();
                });

                $router->respond('GET', '/Masters/Liability.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/liability/', 301)->send();
                });

                $router->respond('GET', '/Masters/Licensing.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/liability/', 301)->send();
                });

                $router->respond('GET', '/Masters/National.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/national/', 301)->send();
                });

                $router->respond('GET', '/Masters/PlanB.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/plan-b/', 301)->send();
                });

                $router->respond('GET', '/Masters/counseling.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/counseling/', 301)->send();
                });

                $router->respond('GET', '/Masters/courses.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/courses/', 301)->send();
                });

                $router->respond('GET', '/Masters/disclosure.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/disclosure/', 301)->send();
                });

                $router->respond('GET', '/Masters/exam.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/exam/', 301)->send();
                });

                $router->respond('GET', '/Masters/general.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/general/', 301)->send();
                });

                $router->respond('GET', '/Masters/learning.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/learning/', 301)->send();
                });

                $router->respond('GET', '/Masters/personnel.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/personnel/', 301)->send();
                });

                $router->respond('GET', '/Masters/portfolio.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/portfolio/', 301)->send();
                });

                $router->respond('GET', '/Masters/practicum.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/practicum/', 301)->send();
                });

                $router->respond('GET', '/Masters/progress.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/masters/progress/', 301)->send();
                });

                $router->respond('GET', '/Practicums/', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/practicums/', 301)->send();
                });

                $router->respond('GET', '/Practicums/default.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/practicums/', 301)->send();
                });

                $router->respond('GET', '/Practicums/forms.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/practicums/forms/', 301)->send();
                });

                $router->respond('GET', '/Practicums/general.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/practicums/general/', 301)->send();
                });

                $router->respond('GET', '/Practicums/guidelines.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/practicums/guidelines/', 301)->send();
                });

                $router->respond('GET', '/Practicums/supervisor.html', function($request, $response, $service, $app) {
                    $response->redirect($app->site_config['base_url'] . '/programs/cspp/handbook/practicums/supervisor/', 301)->send();
                });

                /**
                 * Routes
                 */
                $router->respond('GET', '/', function($request, $response, $service, $app) {
                    echo $app->template->render('edpsych/programs/cspp/handbooks/index.html', ["section" => "programs-degrees"]);
                });

                $router->with('/advanced-practicum', function() use ($router) {
                    $router->respond('GET', '/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/advanced-practicum/index.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/expectations/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/advanced-practicum/expectations.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/advanced/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/advanced-practicum/advanced.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/documentation/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/advanced-practicum/documentation.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/interview/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/advanced-practicum/interview.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/liability/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/advanced-practicum/liability.html', ["section" => "programs-degrees"]);
                    });
                });

                $router->with('/counseling', function() use ($router) {
                    $router->respond('GET', '/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/index.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/ceu/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/ceu.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/guidelines/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/guidelines.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/k-12/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/k-12.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/minneapolis/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/minneapolis.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/areas/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/areas.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/forms/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/forms.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/information/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/information.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/interview/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/interview.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/letter/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/letter.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/requirements/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/requirements.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/site/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/site.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/taping/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/counseling/taping.html', ["section" => "programs-degrees"]);
                    });
                });

                $router->with('/doctoral', function() use ($router) {
                    $router->respond('GET', '/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/index.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/[8452|8509|8994|:course_number]/', function($request, $response, $service, $app) {
                        $course_number = $request->param('course_number');
                        $template_path = 'edpsych/programs/cspp/handbooks/doctoral/' . $course_number . '.html';
                        echo $app->template->render($template_path, ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/appendix-[a|b|d|e:appendix]/', function($request, $response, $service, $app) {
                        $appendix = $request->param('appendix');
                        $template_name = 'appendix-' . $appendix;
                        $template_path = 'edpsych/programs/cspp/handbooks/doctoral/' . $template_name . '.html';
                        echo $app->template->render($template_path, ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/final-oral/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/final-oral.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/liability/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/liability.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/licensing/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/licensing.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/certification/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/certification.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/disclosure/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/disclosure.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/general/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/general.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/graduation/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/graduation.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/internship/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/internship.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/personnel/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/personnel.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/practicum/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/practicum.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/preliminary/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/preliminary.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/procedures/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/procedures.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/seminar/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/seminar.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/thesis/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/doctoral/thesis.html', ["section" => "programs-degrees"]);
                    });
                });

                $router->with('/masters', function() use ($router) {
                    $router->respond('GET', '/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/index.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/appendix-[a|c|d|e|l:appendix]/', function($request, $response, $service, $app) {
                        // "a", "c", "d" or "l" get assigned to $appendix
                        $appendix = $request->param('appendix');

                        // Use that to build the name of the template
                        $template_name = 'appendix-' . $appendix;

                        // Put the path together and use it to render the correct template
                        $template_path = 'edpsych/programs/cspp/handbooks/doctoral/' . $template_name . '.html';
                        echo $app->template->render($template_path, ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/commencement/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/commencement.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/liability/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/liability.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/licensing/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/licensing.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/national/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/national.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/plan-b/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/plan-b.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/counseling/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/counseling.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/courses/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/courses.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/disclosure/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/disclosure.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/exam/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/exam.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/general/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/general.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/learning/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/learning.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/personnel/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/personnel.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/portfolio/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/portfolio.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/practicum/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/practicum.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/progress/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/masters/progress.html', ["section" => "programs-degrees"]);
                    });
                });

                $router->with('/practicums', function() use ($router) {
                    $router->respond('GET', '/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/practicums/index.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/forms/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/practicums/forms.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/general/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/practicums/general.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/guidelines/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/practicums/guidelines.html', ["section" => "programs-degrees"]);
                    });

                    $router->respond('GET', '/supervisor/', function($request, $response, $service, $app) {
                        echo $app->template->render('edpsych/programs/cspp/handbooks/practicums/supervisor.html', ["section" => "programs-degrees"]);
                    });
                });
            });
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
            $response->redirect($app->site_config['base_url'] . '/research/', 301)->send();
        });

        $router->respond('GET', '/labs/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/research/labs/', 301)->send();
        });

        $router->respond('GET', '/topics/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/research/topics/', 301)->send();
        });

        $router->respond('GET', '/student-dissertations/default.html', function($request, $response, $service, $app) {
            $response->redirect($app->site_config['base_url'] . '/research/student-dissertations/', 301)->send();
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

    $router->respond('404', function ($request) {
        $page = $request->uri();
        echo "Oops, it looks like $page doesn't exist..\n";
    });
});

$router->dispatch(null, new \CEHD\App\Response\BaseResponse());
