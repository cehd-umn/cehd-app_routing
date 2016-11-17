<?php
// require_once __DIR__ . '/../../vendor/autoload.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

$router = new \Klein\Klein();

// TODO Re-evaluate how this config is managed
class Config {
    const BASE_URL = '/edpsych-twig';
    const DEBUG = true;
    const DEPT_NAME = "Department of Educational Psychology";
}

// TODO Move this out of the global namespace
// TODO make this a private function?
function twig_bootstrap() {
    require_once __DIR__ . '/../../../vendor/twig/twig/lib/Twig/Autoloader.php';
    Twig_Autoloader::register();

    $loader = new Twig_Loader_Filesystem(__DIR__ . '/../../templates');
    $twig = new Twig_Environment($loader, array(
        'cache' => __DIR__ . '/../../../cache', // Look here if vars don't show up
        'debug' => Config::DEBUG,
        'autoescape' => true,
    ));
    return $twig;
}

$router->respond(function($request, $response, $service, $app) use ($router) {
    $app->register('template', function() {

        $asset_base = Config::BASE_URL . '/assets';
        $css_base = $asset_base . '/dist/css';
        $js_base = $asset_base . '/dist/js';
        $cehd_edpsych_nav_open = false;
        $twig = twig_bootstrap();
        $config = [
            'asset_base' => $asset_base,
            'base_url' => Config::BASE_URL,
            'copyright_year' => date('Y'),
            'css_base' => $css_base,
            'dept_name' => Config::DEPT_NAME,
            'js_base' => $js_base,
        ];

        // Check for main nav state
        if (array_key_exists('cehd-edpsych-navOpen', $_COOKIE)) {
            $cehd_edpsych_nav_open = $_COOKIE['cehd-edpsych-navOpen'];
        }

        $twig->addGlobal('config', $config);
        $twig->addGlobal('cehd_edpsych_nav_open', $cehd_edpsych_nav_open);
        return $twig;
    });
});

$router->with(Config::BASE_URL, function() use ($router) {

    // Ed Psych HOME
    $router->respond('GET', '/', function($request, $response, $service, $app) {
        echo $app->template->render('edpsych/index.html', []);
    });

    // ABOUT
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

    // ALUMNI
    $router->with('/alumni', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/alumni/index.html', []);
        });
    });

    // CURRENT STUDENTS
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

    // GIVING
    // TODO Figure out what's happening with forms
    $router->with('/forms', function() use ($router) {
        /**
         * Redirects
         */
        $router->respond('GET', '/giving.html', function($request, $response, $service, $app) {
            $response->redirect(Config::BASE_URL . '/forms/giving/', 301)->send();
        });

        /**
         * Routes
         */
        $router->respond('GET', '/giving/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/forms/index.html', []);
        });
    });

    /**
     * People
     */
    $router->with('/people', function() use ($router) {
        $router->respond('GET', '/', function($request, $response, $service, $app) {
            echo $app->template->render('edpsych/people/index.html', ["section" => "people"]);
        });

        /**
         * People Tool Feed
         */
        $router->respond('GET', '/people-tool/', function($request, $response, $service, $app) {
            $uri ="https://apps.cehd.umn.edu/people/admin/RSSFeed.aspx?tg=213";

            // Init curl
            $ch = curl_init($uri);

            // curl_setopt($ch, CURLOPT_HEADER, true);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_URL, $uri);
            $content = curl_exec($ch);

            // list($header, $contents) = preg_split('/([\r\n][\r\n])\\1/', curl_exec($ch), 2);
            // // $status = curl_getinfo($ch);
            // curl_close($ch);
            // $header_text = preg_split('/[\r\n]+/', $header);

            // Propagate headers to response.
            // foreach ($header_text as $header) {
            //     if (preg_match('/^(?:Content-Type|Content-Language|Set-Cookie):/i', $header)) {
            //         header($header);
            //     }
            // }

            // Send along the contents
            // echo $contents;
            // echo file_get_contents($uri);
            echo $content;
        });

        /**
         * Faculty
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
            $router->respond('GET', '/penny/', function($request, $response, $service, $app) {
                echo $app->template->render('edpsych/people/faculty/penny.html', ["section" => "people"]);
            });
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

    // RESEARCH
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
$router->dispatch();
