<?php
namespace CEHD\App\CEHD;

require_once __DIR__ . '/../../../cehd/app/App/App.php';
require_once __DIR__ . "/../../../cehd/app/XML/Event.php";
require_once __DIR__ . "/../../../cehd/app/XML/InfoSession.php";
require_once __DIR__ . '/../../../cehd/app/XML/News.php';
require_once __DIR__ . '/../../../cehd/app/XML/People.php';
require_once __DIR__ . '/../../../cehd/app/XML/Search.php';
require_once __DIR__ . '/../../../cehd/app/Card/ProgramCard.php';
require_once __DIR__ . '/../../../cehd/app/Card/TopicCard.php';
require_once __DIR__ . '/../../../cehd/app/Services/ServicesApp.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use CEHD\Services\ServicesApp;
use CEHD\XML\Search\Search;

class CEHDApp extends \CEHD\App\App\BaseApp
{
    // @todo put this into the config file
    // @todo this can be public

//    protected $_searchApplianceSites = [
//                "primary" => [
//                    "client" => "tc_education",
//                    "name" => "For Students",
//                    "site" => "tc_education_students",
//                ],
//                "secondary" => [
//                    "client" => "tc_education",
//                    "name" => "College of Education + Human Development",
//                    "site" => "tc_education",
//                ],
//                "tertiary" => [
//                    "client" => "searchumn",
//                    "name" => "University of Minnesota",
//                    "site" => "default_collection",
//                    "url" => "www.umn.edu",
//                ]
//            ];

    public function __construct($file_paths)
    {
        parent::__construct($file_paths);

//        $this->template->addGlobal(
//            "gsa_sites",
//            $this->_searchApplianceSites
//        );

        // Add a cachebuster for the css
        $this->template->addGlobal(
            "css_cachebuster",
            $this->getCacheBusterQueryString(
                __DIR__ . "/../../../public/assets/dist/css/minified/cehd.css"
            )
        );

        // Add a cachebuster for the js
        $this->template->addGlobal(
            "js_cachebuster",
            $this->getCacheBusterQueryString(
                __DIR__ . "/../../../public/assets/dist/js/app.min.js"
            )
        );

        $this->router->onHttpError([$this, 'errorPageHandler']);
    }

    public function errorPageHandler($code=404, $router)
    {
        $html = $this->_renderTemplate('cehd/error/404.html', []);
        $router->response()->status($code);
        $router->response()->body($html);
    }

    public function homePageHandler($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . '/../../../cehd/config/cehd/homepage_videos.yml';
        $video_data = $this->loadYaml($yaml_file_path);

        foreach ($video_data as $key => $video) {
            $file_path = __DIR__ . "/../../../public" . $video["source"];
            $video_data[$key]["cachebuster"] = $this->getCacheBusterQueryString($file_path);
        }

        $template_data = [
            "videos" => $video_data,
        ];
        echo $this->_renderTemplate('cehd/index.html', $template_data);
    }

    private function _processSorterData($yaml_file_path)
    {
        $data = $this->loadYaml($yaml_file_path);
        ksort($data);
        $category_data = [];
        $categories = [];
        $links = [];

        foreach ($data as $item_index => $item) {
            $data[$item_index]["categories_hyphenated"] = [];
            $data[$item_index]["links_hyphenated"] = [];

            if ($item["categories"]) {
                foreach ($item["categories"] as $category_index => $category) {
                    $hyphenated = $this->hyphenate($category);
                    array_push($data[$item_index]["categories_hyphenated"], $hyphenated);
                    $categories[$hyphenated] = $category;
                }
            }

            if (array_key_exists("links", $item) && $item["links"]) {
                foreach ($item["links"] as $degree_index => $degree) {
                    $hyphenated = $this->hyphenate($degree["title"]);
                    array_push($data[$item_index]["links_hyphenated"], $hyphenated);
                    $links[$hyphenated] = $degree["title"];
                }
            }
        }

        asort($categories);
        asort($links);
        $category_data =  $categories + $links;
        return [
            "data" => $data,
            "categories" => $category_data,
        ];
    }

    public function programsPageHandler($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . '/../../../cehd/config/cehd/programs.yml';
        $cardsInstance = new \CEHD\App\Card\ProgramCard\ProgramCards();
        $cards = $cardsInstance->setCards($yaml_file_path)->getCards();
        $categories = $cardsInstance->getCategoriesAndDegrees();
        $template_data = [
            "section" => "academics",
            "item_data" => $cardsInstance->getCards(),
            "categories" => $categories,
            "item_data_json" => json_encode($cards),
        ];
        echo $this->_renderTemplate('cehd/programs/index.html', $template_data);
    }


    public function topicsPageHandler($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . '/../../../cehd/config/cehd/experts.yml';
        $cardsInstance = new \CEHD\App\Card\TopicCard\TopicCards();

        $cards = $cardsInstance->setCards($yaml_file_path)
            ->nullifyDescriptions()
            ->getCards();

        $template_data = [
            "section" => "academics",
            "titles_are_links" => true,
            "item_data" => $cards,
            "categories" => $cardsInstance->getCategoriesAndInternalCategories(),
            "item_data_json" => json_encode($cards),
        ];

        echo $this->_renderTemplate('cehd/topics/index.html', $template_data);
    }

    public function topicPageHandler($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . '/../../../cehd/config/cehd/experts.yml';
        $cardsInstance = new \CEHD\App\Card\TopicCard\TopicCards();
        $topics = $cardsInstance->setCards($yaml_file_path)->getCardsWithHyphenatedKeys();

        $topic_data = array_key_exists($request->topic, $topics) ? $topics[$request->topic] : null;

        if ($topic_data) {
            $template_data = [
                "section" => "topics",
                "topic_data" => $topic_data,
                "topic_data_json" => json_encode($topic_data),
            ];
            echo $this->_renderTemplate('cehd/topics/topic.html', $template_data);
        }
    }

    public function personPageHandler($request, $response, $service, $app)
    {
        $template_data = [
            "section" => "people",
            "internet_id" => $request->internetId,
        ];
        echo $this->_renderTemplate('cehd/people/person.html', $template_data);
    }

    public function giftsPageHandler($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . '/../../../cehd/config/cehd/gifts_page_appointments.yml';
        $appointments = $this->loadYaml($yaml_file_path);
        $template_data = [
            "section" => null,
            "appointments" => $appointments,
        ];
        echo $this->_renderTemplate('cehd/giving/gifts.html', $template_data);
    }

    public function eventsPageHandler($request, $response, $service, $app)
    {
        $query_data = $request->params();
        if ($this->arrayContains("z", $query_data)) {
            $route = "/events/" . $query_data["z"] . "/";
            $response->redirect($route, 301)->send();
        }

        echo $this->_renderTemplate("cehd/events/index.html", ["section" => "events"]);
    }

    public function eventPageHandler($request, $response, $service, $app)
    {
        $services = new ServicesApp();
        $event = $services->getEvent($request->id, $response);
        echo $this->_renderTemplate("cehd/events/event.html", ["event" => $event]);
    }

    public function peopleSearchResultsPageHandler($request, $response, $service, $app)
    {
        $query_data = $request->params();
        $nm = null;

        if (array_key_exists("nm", $query_data)) {
            $nm = $query_data["nm"];
        }

        $template_data = [
            "section" => null,
            "nm" => $nm,
        ];
        echo $this->_renderTemplate("cehd/directory/results.html", $template_data);
    }
}
