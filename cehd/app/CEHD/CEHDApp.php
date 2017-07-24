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
require_once __DIR__ . '/../../../cehd/app/Card/PatternCard.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use CEHD\App\Card\PatternCard\PatternCards;

class CEHDApp extends \CEHD\App\App\BaseApp
{
    private $_sites = [
                "primary" => [
                    "name" => "CEHD",
                    "url" => "www.cehd.umn.edu",
                ],
                "secondary" => [
                    "name" => "UMN",
                    "url" => "www.umn.edu",
                ]
            ];

    private $_patternLibraryData;

    private $_patternCardsInstance;

    function __construct($file_paths)
    {
        parent::__construct($file_paths);

        // Add a cachebuster for the css
        $this->template->addGlobal(
            "css_cachebuster",
            $this->getCacheBusterQueryString(
                __DIR__ . "/../../../public/assets/dist/css/minified/cehd.css"));

        // Add a cachebuster for the js
        $this->template->addGlobal(
            "js_cachebuster",
            $this->getCacheBusterQueryString(
                __DIR__ . "/../../../public/assets/dist/js/cehd.min.js"));

        $this->router->onHttpError([$this, 'errorPageHandler']);

        if (!$this->config["is_prod"])
        {
            $yaml_file_path = __DIR__ . '/../../../cehd/config/cehd/patterns.yml';
            $this->_patternCardsInstance = new PatternCards();
            $this->_patternCardsInstance->setCards($yaml_file_path);

            $this->_patternLibraryData = $this->_patternCardsInstance->buildRoutesFromPatternData();;

            foreach ($this->_patternLibraryData as $route => $card)
            {
                $handler = "patternPageHandler";

                if ($card->handler)
                {
                    $handler = $card->handler;
                }
                $this->router->respond('GET', $route, [$this, $handler]);
            }
        }
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

        foreach ($video_data as $key => $video)
        {
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

        foreach ($data as $item_index => $item)
        {
            $data[$item_index]["categories_hyphenated"] = [];
            $data[$item_index]["links_hyphenated"] = [];

            if ($item["categories"])
            {
                foreach ($item["categories"] as $category_index => $category)
                {
                    $hyphenated = $this->hyphenate($category);
                    array_push($data[$item_index]["categories_hyphenated"], $hyphenated);
                    $categories[$hyphenated] = $category;
                }
            }

            if (array_key_exists("links", $item) && $item["links"])
            {
                foreach ($item["links"] as $degree_index => $degree)
                {
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

        if ($topic_data)
        {
            $template_data = [
                "section" => "topics",
                "topic_data" => $topic_data,
                "topic_data_json" => json_encode($topic_data),
            ];
            echo $this->_renderTemplate('cehd/topics/topic.html', $template_data);
        }
    }

    public function patternsHomePageHandler($request, $response, $service, $app)
    {
        if (!$this->config["is_prod"])
        {
            $cards = $this->_patternCardsInstance->getCards();

            // @todo this should probably go in the pattern card class
            foreach ($cards as $title => $pattern)
            {
                $path = $this->_patternCardsInstance->buildRouteFromPattern($pattern->group, $title);
                $link = [
                    "href" => $path,
                    "title" => $title,
                ];
                $cards[$title]->links = [$link];
            }

            $template_data = [
                "section" => "patterns",
                "item_data" => $cards,
                "categories" => ["Categories" => $this->_patternCardsInstance->getCategories()],
                "groups" => $this->_patternCardsInstance->getGroupNames(),
                "item_data_json" => json_encode($cards),
            ];

            echo $this->_renderTemplate('cehd/patterns/index.html', $template_data);
        }
    }

    public function patternGroupHandler($request, $response, $service, $app)
    {
        $uri_parts = explode("/", $request->pathname());
        $group = $uri_parts[3];
        $patterns = $this->_patternCardsInstance->getGroup($group);

        if ($patterns)
        {
            echo $this->_renderTemplate("cehd/patterns/patterns.html", ["patterns" => $patterns, "page_title" => $group]);
        }
    }

    public function searchPageHandler($request, $response, $service, $app)
    {
        $search_client = new \CEHD\App\Search\Search($this->_sites);
        $query_data = $request->params();
        $filter = "CEHD";

        if ($this->arrayContains("q", $query_data))
        {
            $search_query = array_key_exists("q", $query_data) ? $query_data["q"] : "";
        }
        elseif ($this->arrayContains("query", $query_data))
        {
            $search_query = array_key_exists("query", $query_data) ? $query_data["query"] : "";
        }

        if ($this->arrayContains("filter", $query_data))
        {
            $filter = $query_data["filter"];
        }

        $search_client_query_data = [
            "q" => $search_query,
            "sites" => ["primary", "secondary"],
        ];

        $results = $search_client->getResults($search_client_query_data);
        $template_data = [
            "results" => $results,
            "filter" => $filter,
            "query" => $search_query,
        ];

        echo $this->_renderTemplate('cehd/search/index.html', $template_data);
    }

    public function searchEndpointHandler($request, $response, $service, $app)
    {
        $search_client = new \CEHD\App\Search\Search($this->_sites);
        $query_data = $request->params();
        $results = $search_client->getResults($query_data);
        return $response->sendJSONResponse(json_encode($results));
    }

    public function peopleEndpointHandler($request, $response, $service, $app)
    {
        $base_url =  'https://apps.cehd.umn.edu/people/admin/XMLFeed.aspx';
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

        if (array_key_exists('nm', $query_data))
        {
            $query['nm'] = $query_data['nm'];
        };

        if (
            array_key_exists('en', $query_data) ||
            array_key_exists('tg', $query_data) ||
            array_key_exists('nm', $query_data))
        {
            $http_client = $this->bootstrapHTTPClient([]);
            $client_query_data = ['query' => $query];
            $client_response = $http_client->request('GET', $base_url, $client_query_data);
            $people_obj = new \CEHD\App\People\People($client_response->getBody());
            $response->sendJSONResponse(json_encode($people_obj->people));
        }
    }

    // @todo this method should go in a BaseApp class
    public function personEndpointHandler($request, $response, $service, $app)
    {
        $query_data = $request->params();
        $base_url =  'https://apps.cehd.umn.edu/people/admin/XMLFeed.aspx';

        if (array_key_exists('internetId', $query_data))
        {
            $http_client = $this->bootstrapHTTPClient([]);
            $client_query_data = ['query' => [
                'id' => $query_data['internetId'
                ]
            ]];
            $client_response = $http_client->request('GET', $base_url, $client_query_data);
            $people_obj = new \CEHD\App\People\People($client_response->getBody());
            $response->sendJSONResponse($people_obj->people[0]->toJSON());
        }
    }

    public function newsFeedEndpointHandler($request, $response, $service, $app)
    {
        $base_url = "http://news.cehd.umn.edu/";
        $query_data = $request->params();

        if (array_key_exists("feed_url", $query_data) && !empty($query_data["feed_url"]))
        {
            $base_url = $query_data["feed_url"];
        }

        if (array_key_exists("feed-path", $query_data))
        {
            $http_client = $this->bootstrapHTTPClient([]);
            $request_url = $base_url . $query_data["feed-path"];
            $client_response = $http_client->request("GET", $request_url);
            $xml = $client_response->getBody();
            $news = new \CEHD\App\XML\News\News($xml);

            if ($this->arrayContains("sortBy", $query_data) && $query_data["sortBy"] == "categoryDecade")
            {
                $news->sortByCategoryDecade();
            }
            $response->sendJSONResponse(json_encode($news->stories));
        }
    }

    public function infoSessionsEndpointHandler($request, $response, $service, $app)
    {
        $base_url = "https://apps.cehd.umn.edu/Students/Infosession/Admin/RSSFeed.aspx";
        $query_data = $request->params();
        if (array_key_exists("code", $query_data))
        {
            $http_client = $this->bootstrapHTTPClient([]);
            $client_query_data = ["query" => [
                "code" => $query_data["code"],
                ]
            ];
            $request_url = $base_url;
            $client_response = $http_client->request("GET",$base_url, $client_query_data);
            $xml = $client_response->getBody();
            $infoSessions = new \CEHD\XML\InfoSession\InfoSessions($xml);
            $response->sendJSONResponse(json_encode($infoSessions->infoSessions));
        }
    }

    public function getEvent($event_id)
    {
        $base_url =  "https://apps.cehd.umn.edu/events/Admin/XMLFeed.aspx";
        $http_client = $this->bootstrapHTTPClient([]);
        $client_query_data = ["query" => [
            "z" => $event_id,
            ]
        ];
        $client_response = $http_client->request("GET", $base_url, $client_query_data);
        $events =  new \CEHD\XML\Event\Events($client_response->getBody());
        return $events->events[0];
    }

    public function getEventJSON($event_id, $response)
    {
        return $response->sendJSONResponse(json_encode($this->getEvent($event_id)));
    }

    public function eventEndpointHandler($request, $response, $service, $app)
    {
        $query_data = $request->params();
        if (array_key_exists("z", $query_data))
        {
            return $this->getEventJSON($query_data["z"], $response);
        }
    }

    public function eventsFeedEndpointHandler($request, $response, $service, $app)
    {
        $base_url =  "https://apps.cehd.umn.edu/events/Admin/XMLFeed.aspx";
        $query_data = $request->params();
        if (array_key_exists("r", $query_data) &&
                array_key_exists("t", $query_data) &&
                array_key_exists("q", $query_data)) {
            $http_client = $this->bootstrapHTTPClient([]);
            $client_query_data = ["query" => [
                "r" => $query_data["r"],
                "t" => $query_data["t"],
                "q" => $query_data["q"],
                ]
            ];
            $client_response = $http_client->request("GET",$base_url, $client_query_data);
            $events =  new \CEHD\XML\Event\Events($client_response->getBody());
            $response->sendJSONResponse(json_encode($events->events));
        }
    }

    public function programsFeedEndpointHandler($request, $response, $service, $app)
    {
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
            $http_client = $this->bootstrap_helpers->bootstrap_http_client([]);
            $client_response = $http_client->request('GET', $base_url, $client_query_data);
            $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
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

    public function patternPageHandler($request, $response, $service, $app)
    {
        $path = $request->pathname();
        $pattern = $this->_patternLibraryData[$path];

        if ($pattern)
        {
            if ($pattern->type === "include")
            {
                echo $this->_renderTemplate("cehd/patterns/pattern.html", ["pattern" => $pattern]);
            }
            else if ($pattern->type === "template")
            {
                echo $this->_renderTemplate($pattern->template, ["pattern" => $pattern]);
            }
        }
    }

    public function eventsPageHandler($request, $response, $service, $app)
    {
        $query_data = $request->params();
        if ($this->arrayContains("z", $query_data))
        {
            $route = "/events/" . $query_data["z"] . "/";
            $response->redirect($route, 301)->send();
        }

        echo $this->_renderTemplate("cehd/events/index.html", ["section" => "events"]);
    }

    public function eventPageHandler($request, $response, $service, $app)
    {
        $event = $this->getEvent($request->id, $response);
        echo $this->_renderTemplate("cehd/events/event.html", ["event" => $event]);
    }

    public function peopleSearchResultsPageHandler($request, $response, $service, $app)
    {
        $query_data = $request->params();
        $nm = null;

        if (array_key_exists("nm", $query_data))
        {
            $nm = $query_data["nm"];
        }

        $template_data = [
            "section" => null,
            "nm" => $nm,
        ];
        echo $this->_renderTemplate("cehd/directory/results.html", $template_data);
    }
}
