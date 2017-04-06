<?php
namespace CEHD\App\CEHD;

require_once __DIR__ . '/../../../cehd/app/App/App.php';
require_once __DIR__ . "/../../../cehd/app/XML/Event.php";
require_once __DIR__ . '/../../../cehd/app/XML/News.php';
require_once __DIR__ . '/../../../cehd/app/XML/People.php';
require_once __DIR__ . '/../../../cehd/app/XML/Search.php';

class CEHDApp extends \CEHD\App\App\BaseApp
{
    public $nav_open = false;

    private $_sites = [
                "primary" => [
                    "name" => "College of Education + Human Development",
                    "url" => "www.cehd.umn.edu",
                ],
                "secondary" => [
                    "name" => "University of Minnesota",
                    "url" => "www.umn.edu",
                ]
            ];

    function __construct($file_paths)
    {
        parent::__construct($file_paths);

        // @todo does the college site need this?
        // Check for main nav state
        if (array_key_exists('cehd-cehd-navOpen', $_COOKIE)) {
            $this->nav_open = $_COOKIE['cehd-cehd-navOpen'];
        }
        $this->template->addGlobal('cehd_cehd_nav_open', $this->nav_open);
    }

    public function programsPageHandler($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . '/../../../cehd/config/cehd/program_sorter.yml';
        $program_data = $this->loadYaml($yaml_file_path);
        $categories = [];
        $degrees = [];

        // @todo this whole block is not DRY
        foreach ($program_data as $program_index => $program)
        {
            $program_data[$program_index]["categories_hyphenated"] = [];
            $program_data[$program_index]["degrees_hyphenated"] = [];

            if ($program["categories"])
            {
                foreach ($program["categories"] as $category_index => $category)
                {
                    $hyphenated = $this->hyphenate($category);
                    array_push($program_data[$program_index]["categories_hyphenated"], $hyphenated);
                    $categories[$hyphenated] = $category;
                }
            }

            if ($program["degrees"])
            {
                foreach ($program["degrees"] as $degree_index => $degree)
                {
                    $hyphenated = $this->hyphenate($degree);
                    array_push($program_data[$program_index]["degrees_hyphenated"], $hyphenated);
                    $degrees[$hyphenated] = $degree;
                }
            }
        }

        asort($categories);
        asort($degrees);

        $template_data = [
            "section" => "programs",
            "program_data" => $program_data,
            "categories" => $categories,
            "degrees" => $degrees,
            "program_data_json" => json_encode($program_data),
        ];

        echo $this->_renderTemplate('cehd/programs/index.html', $template_data);
    }

    private function _parseTopicData()
    {
        $yaml_file_path = __DIR__ . '/../../../cehd/config/cehd/experts.yml';
        $topic_data = $this->buildHyphenatedKeys(
            $this->loadYaml($yaml_file_path));
        $categories = [];

        foreach ($topic_data as $key => $topic)
        {
            $topic_data[$key]["categories_hyphenated"] = [];

            if ($topic["categories"])
            {
                foreach ($topic["categories"] as $category_index => $category)
                {
                    $hyphenated = $this->hyphenate($category);
                    array_push($topic_data[$key]["categories_hyphenated"], $hyphenated);
                    $categories[$hyphenated] = $category;
                }
            }
        }

        asort($categories);

        return [
            "topics" => $topic_data,
            "categories" => $categories,
        ];
    }

    public function topicsPageHandler($request, $response, $service, $app)
    {
        $topics_and_cats = $this->_parseTopicData();

        $template_data = [
            "section" => "topics",
            "topic_data" => $topics_and_cats["topics"],
            "categories" => $topics_and_cats["categories"],
            "topic_data_json" => json_encode($topics_and_cats["topics"]),
        ];

        echo $this->_renderTemplate('cehd/topics/index.html', $template_data);
    }

    public function topicPageHandler($request, $response, $service, $app)
    {
        $topics_and_cats = $this->_parseTopicData();
        $topics = $topics_and_cats["topics"];
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

    public function searchPageHandler($request, $response, $service, $app)
    {
        // Lazily load the search client so it's not loaded on unrelated
        // requests
        $search_client = new \CEHD\App\Search\Search($this->_sites);
        $query_data = $request->params();
        $search_query = array_key_exists("q", $query_data) ? $query_data["q"] : "";
        $search_client_query_data = [
            "q" => $search_query,
            "sites" => ["primary", "secondary"],
        ];

        $results = $search_client->getResults($search_client_query_data);
        $template_data = [
            "results" => $results,
            "query" => $query_data["q"],
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

        if (array_key_exists('en', $query_data) || array_key_exists('tg', $query_data))
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
        if (array_key_exists("feed-path", $query_data))
        {
            $http_client = $this->bootstrapHTTPClient([]);
            $request_url = $base_url . $query_data["feed-path"];
            $client_response = $http_client->request("GET", $request_url);
            $xml = $client_response->getBody();
            $news = new \CEHD\App\XML\News\News($xml);
            $response->sendJSONResponse(json_encode($news->stories));
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
}
