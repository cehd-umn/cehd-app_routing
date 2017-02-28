<?php
namespace CEHD\App\EdPsych;

require_once __DIR__ . '/../../../cehd/app/App/App.php';
require_once __DIR__ . '/../../../cehd/app/Search/Search.php';

class EdPsychApp extends \CEHD\App\App\BaseApp
{
    public $nav_open = false;

    protected $_search_client;

    // @todo move routes in public/edpsych/index.php here
    function __construct($file_paths)
    {
        parent::__construct($file_paths);

        $this->_search_client = new \CEHD\App\Search\Search(
            [
                "primary" => [
                    "name" => "Educational Psychology",
                    "url" => "www.cehd.umn.edu/edpsych/",
                ],
                "secondary" => [
                    "name" => "College of Education + Human Development",
                    "url" => "www.cehd.umn.edu",
                ],
                "tertiary" => [
                    "name" => "University of Minnesota",
                    "url" => "www.umn.edu",
                ]
            ]
        );

        // Check for main nav state
        if (array_key_exists('cehd-edpsych-navOpen', $_COOKIE)) {
            $this->nav_open = $_COOKIE['cehd-edpsych-navOpen'];
        }

        $this->template->addGlobal('cehd_edpsych_nav_open', $this->nav_open);

        $this->router->respond('GET', '/edpsych/examples/', function($request, $response, $service, $app) {
            // Only expose this route if not in production
            if (!$app->config['is_prod'])
            {
                echo $app->template->render('edpsych/examples/index.html', []);
            }
        });

        $this->router->respond('GET', '/edpsych/examples/macros/', function($request, $response, $service, $app) {
            // Only expose this route if not in production
            if (!$app->config['is_prod'])
            {
                echo $app->template->render('edpsych/examples/macros.html', []);
            }
        });

        $this->router->respond('GET', '/edpsych/search/', function($request, $response, $service, $app) {
            $query_data = $request->params();
            $query = array_key_exists("q", $query_data) ? $query_data["q"] : "";

            $client_query_data = [
                "q" => $query,
                "sites" => ["primary", "secondary", "tertiary"],
            ];

            $results = $app->_search_client->get($client_query_data);
            $template_data = [
                "results" => $results,
                "query" => $query_data["q"],
            ];

            echo $app->template->render('edpsych/search.html', $template_data);
        });

        /**
         * People
         */
        $this->router->with('/edpsych/people', function() {
            $this->router->respond('GET', '/default.html', function($request, $response, $service, $app) {
                $response->redirect($app->config['base_url'] . '/people/', 301)->send();
            });

            $this->router->respond('GET', '/', function($request, $response, $service, $app) {
                $yaml_file_path = __DIR__ . '/../../../cehd/config/edpsych/people/index.yml';
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

            $this->router->respond('GET', '/[:internetId]/', function($request, $response, $service, $app) {
                $yaml_file_path = __DIR__ . '/../../../cehd/config/edpsych/people/extra_content.yml';
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

            $this->router->respond('GET', '/edpsych/programs/courses/[asl|qme|psyf:course]/', function($request, $response, $service, $app) {
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
        });
    }
}
