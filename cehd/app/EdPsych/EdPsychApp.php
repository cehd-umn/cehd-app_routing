<?php
namespace CEHD\App\EdPsych;

require_once __DIR__ . "/../../../cehd/app/App/App.php";
require_once __DIR__ . "/../../../cehd/app/XML/Event.php";
require_once __DIR__ . "/../../../cehd/app/XML/News.php";
require_once __DIR__ . "/../../../cehd/app/XML/People.php";
require_once __DIR__ . "/../../../cehd/app/XML/Search.php";

class EdPsychApp extends \CEHD\App\App\BaseApp
{
    public $nav_open = false;

    private $_search_client;

    protected $_search_template;

    protected $_searchApplianceSites;

    function __construct($file_paths)
    {
        parent::__construct($file_paths);

        // @todo put this into the config file
        $this->_searchApplianceSites = [
                "primary" => [
                    "client" => "tc_education",
                    "name" => "Educational Psychology",
                    "site" => "tc_education",
                    "url" => "www.cehd.umn.edu/edpsych/",
                ],
                "secondary" => [
                    "client" => "tc_education",
                    "name" => "College of Education + Human Development",
                    "site" => "tc_education_students",
                ],
                "tertiary" => [
                    "client" => "searchumn",
                    "name" => "University of Minnesota",
                    "site" => "default_collection",
                    "url" => "www.umn.edu",
                ]
            ];

        $inline_nav_styles_file = __DIR__ . "/../../../public/assets/peloton/latest/dist/css/minified/nav.css";

        $this->template->addGlobal(
            "inline_nav_styles",
            file_get_contents($inline_nav_styles_file)
        );

        $this->template->addGlobal(
            "gsa_sites",
            $this->_searchApplianceSites
        );

        // Check for main nav state
        if (array_key_exists("cehd-edpsych-navOpen", $_COOKIE)) {
            $this->nav_open = $_COOKIE["cehd-edpsych-navOpen"];
        }

        $this->template->addGlobal("cehd_edpsych_nav_open", $this->nav_open);

        $this->router->onHttpError([$this, 'errorPageHandler']);

        $this->_search_template = "edpsych/search.html";
    }

    public function errorPageHandler($code=404, $router)
    {
        $html = $this->_renderTemplate('edpsych/error/404.html', []);
        $router->response()->status($code);
        $router->response()->body($html);
    }

    // @todo roll this into routes.yml
    public function programsCoursePageHandler($request, $response, $service, $app)
    {
        // values for the "p" query parameter in the JavaScript request
        $p_params = [
            "asl" => [
                "meta_description" => "Our ASL program offers a variety of courses to meet and fulfill your language requirement.",
                "p" => "EPSY_ASL",
                "title" => "American Sign Language",
            ],
            "qme" => [
                "meta_description" => "View brief descriptions of the courses offered for a degree in quantitative methods in education.",
                "p" => "EPSY_PSE",
                "title" => "Quantitative Methods in Education",
            ],
            "psyf" => [
                "meta_description" => "View brief descriptions of the courses offered for a degree in psychological foundations of education.",
                "p" => "EPSY_PSYF",
                "title" => "Psychological Foundations of Education",
            ],
        ];
        $course = $request->param("course");
        $template_data = [
            "meta_description" => $p_params[$course]["meta_description"],
            "p_param" => $p_params[$course]["p"],
            "section" => "programs-degrees",
            "title_text" => $p_params[$course]["title"],
        ];

        echo $this->_renderTemplate("edpsych/programs/courses/course.html", $template_data);
    }

    public function peoplePageHandler($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . "/../../../cehd/config/edpsych/people/index.yml";
        $section_data = $this->loadYaml($yaml_file_path);

        foreach ($section_data as $key => $section)
        {
            $section_data[$key]["section_hyphenated"] = $this->hyphenate($section_data[$key]["section"]);
        };

        $template_data = [
            "meta_description" => "Come learn from top researchers and scientist practitioners in learning and cognition, educational technologies, social psychological and developmental processes, quantitative methods in education, school psychology, counseling and student personnel psychology, and special education.",
            "section" => "people",
            "section_data" => $section_data,
        ];

        echo $this->_renderTemplate("edpsych/people/index.html", $template_data);
    }

    public function personPageHandler($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . "/../../../cehd/config/edpsych/people/extra_content.yml";
        $yaml_data = $this->loadYaml($yaml_file_path);
        $extra_content_data = new \UMN\CEHD\Dict\Dict($yaml_data);
        $extra_content = $extra_content_data->get($request->internetId);

        $template_data = [
            "section" => "people",
            "internet_id" => $request->internetId,
            "extra_content" => $extra_content,
        ];
        echo $this->_renderTemplate("edpsych/people/person.html", $template_data);
    }
}
