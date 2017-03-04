<?php
namespace CEHD\App\CEHD;

require_once __DIR__ . '/../../../cehd/app/App/App.php';
require_once __DIR__ . '/../../../cehd/app/Search/Search.php';
require_once __DIR__ . '/../../../cehd/app/People/People.php';

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
        $program_data = $app->bootstrap_helpers->load_yaml($yaml_file_path);
        $categories = [];

        foreach ($program_data as $program_index => $program)
        {
            $program["categories_hyphenated"] = [];

            if ($program["categories"])
            {
                foreach ($program["categories"] as $category_index => $category)
                {
                    $hyphenated = $app->helpers->hyphenate($category);
                    $program_data[$program_index]["categories_hyphenated"][$category_index] = $hyphenated;
                    $categories[$hyphenated] = $category;
                }
            }
        }

        $template_data = [
            "section" => "programs",
            "program_data" => $program_data,
            "categories" => $categories,
            "program_data_json" => json_encode($program_data),
        ];
        echo $app->_renderTemplate('cehd/programs/index.html', $template_data);
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

        $results = $search_client->get($search_client_query_data);
        $template_data = [
            "results" => $results,
            "query" => $query_data["q"],
        ];

        echo $app->_renderTemplate('cehd/search/index.html', $template_data);
    }

    public function searchEndpointHandler($request, $response, $service, $app)
    {
        $search_client = new \CEHD\App\Search\Search($this->_sites);
        $query_data = $request->params();
        $results = $search_client->get($query_data);
        return $response->sendJSONResponse(json_encode($results));
    }

    public function peopleEndpointHandler($request, $response, $service, $app)
    {
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
            $http_client = $this->bootstrap_helpers->bootstrap_http_client([]);
            $client_query_data = ['query' => $query];
            $client_response = $http_client->request('GET', $base_url, $client_query_data);
            $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
        }
    }

    public function personEndpointHandler($request, $response, $service, $app)
    {
        $query_data = $request->params();
        $base_url =  'https://apps.cehd.umn.edu/people/admin/XMLFeed.aspx';

        if (array_key_exists('internetId', $query_data))
        {
            $http_client = $this->bootstrap_helpers->bootstrap_http_client([]);
            $client_query_data = ['query' => [
                'id' => $query_data['internetId'
                ]
            ]];
            $client_response = $http_client->request('GET', $base_url, $client_query_data);
            $person = new \CEHD\App\People\Person($client_response->getBody());
            $response->sendJSONResponse($person->toJSON());
        }
    }

    public function newsFeedEndpointHandler($request, $response, $service, $app)
    {
        $base_url = "http://news.cehd.umn.edu/";
        $query_data = $request->params();
        if (array_key_exists('feed-path', $query_data))
        {
            $http_client = $this->bootstrap_helpers->bootstrap_http_client([]);
            $request_url = $base_url . $query_data['feed-path'];
            $client_response = $http_client->request('GET', $request_url);
            $xml = $client_response->getBody();
            $xml_obj = $response->xmlStrToObject($xml);
            $result_array = $response->getNewsfeedArrayFromXML($xml_obj);
            $json = json_encode($result_array);
            $response->sendJSONResponse($json);
        }
    }

    public function eventsFeedEndpointHandler($request, $response, $service, $app)
    {
        $base_url =  'https://apps.cehd.umn.edu/events/Admin/XMLFeed.aspx';
        $query_data = $request->params();
        if (array_key_exists('r', $query_data) &&
                array_key_exists('t', $query_data) &&
                array_key_exists('q', $query_data)) {
            $http_client = $this->bootstrap_helpers->bootstrap_http_client([]);
            $client_query_data = ['query' => [
                'r' => $query_data['r'],
                't' => $query_data['t'],
                'q' => $query_data['q'],
                ]
            ];
            $client_response = $http_client->request('GET',$base_url, $client_query_data);
            $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
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

    public function sitemapHandler($request, $response, $service, $app)
    {
        $yaml_file_path = __DIR__ . '/../../../cehd/config/cehd/routes.yml';
        $route_data = $app->bootstrap_helpers->load_yaml($yaml_file_path);
        $template_data = [
            "sitemap_data" => $route_data,
        ];
        echo $app->_renderTemplate('cehd/sitemap.xml', $template_data);
    }
}
