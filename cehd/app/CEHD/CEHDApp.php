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


    private $_http_client;

    // @todo break constructor apart
    // @todo lazily load objects (Search, Person) so they're only instantiated when needed
    // @todo create callbacks (closures) for router
    function __construct($file_paths)
    {
        parent::__construct($file_paths);

        $this->_http_client = $this->bootstrap_helpers->bootstrap_http_client([]);

        // Check for main nav state
        if (array_key_exists('cehd-cehd-navOpen', $_COOKIE)) {
            $this->nav_open = $_COOKIE['cehd-cehd-navOpen'];
        }

        $this->template->addGlobal('cehd_cehd_nav_open', $this->nav_open);

        // $this->router->with('/services', function() {
        //     /**
        //      * People Tool
        //      *
        //      * This makes a request for People Tool data, parses it to JSON and
        //      * sends a JSON blob back
        //      */
        //     $this->router->with('/people-tool', function() {
        //         /**
        //          * GET handler for directory
        //          */
        //         $this->router->respond('GET', '/', function($request, $response, $service, $app) {
        //             $base_url =  'https://apps.cehd.umn.edu/people/admin/RSSFeed.aspx';
        //             $query_data = $request->params();
        //             $query = [];

        //             // Strip out extraneous query params
        //             if (array_key_exists('en', $query_data))
        //             {
        //                 $query['en'] = $query_data['en'];
        //             };

        //             if (array_key_exists('tg', $query_data))
        //             {
        //                 $query['tg'] = $query_data['tg'];
        //             };

        //             if (array_key_exists('en', $query_data) || array_key_exists('tg', $query_data))
        //             {
        //                 $client_query_data = ['query' => $query];
        //                 $client_response = $app->_http_client->request('GET', $base_url, $client_query_data);
        //                 $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
        //             }
        //         });

        //         /**
        //          * GET handler for individual people
        //          */
        //         $this->router->respond('GET', '/person/', function($request, $response, $service, $app) {
        //             $query_data = $request->params();
        //             $base_url =  'https://apps.cehd.umn.edu/people/admin/XMLFeed.aspx';

        //             if (array_key_exists('internetId', $query_data)) {
        //                 $client_query_data = ['query' => [
        //                     'id' => $query_data['internetId'
        //                     ]
        //                 ]];
        //                 $client_response = $app->_http_client->request('GET', $base_url, $client_query_data);
        //                 $person = new \CEHD\App\People\Person($client_response->getBody());
        //                 $response->sendJSONResponse($person->toJSON());
        //             }
        //         });
        //     });

        //     /**
        //      * News Feeds
        //      *
        //      * This makes a request to the news RSS feed, parses it to JSON and
        //      * sends a JSON blob back
        //      */
        //     $this->router->with('/news', function() {
        //         $this->router->respond('GET', '/feed/', function($request, $response, $service, $app) {
        //             $base_url = "http://news.cehd.umn.edu/";
        //             $query_data = $request->params();
        //             if (array_key_exists('feed-path', $query_data))
        //             {
        //                 $request_url = $base_url . $query_data['feed-path'];
        //                 $client_response = $app->_http_client->request('GET', $request_url);
        //                 $xml = $client_response->getBody();
        //                 $xml_obj = $response->xmlStrToObject($xml);
        //                 $result_array = $response->getNewsfeedArrayFromXML($xml_obj);
        //                 $json = json_encode($result_array);
        //                 $response->sendJSONResponse($json);
        //             }
        //         });
        //     });

        //     /**
        //      * Events Calendar
        //      *
        //      * This makes a request to the news RSS feed, parses it to JSON and
        //      * sends a JSON blob back
        //      */
        //     $this->router->with('/events', function() {
        //         $this->router->respond('GET', '/feed/', function($request, $response, $service, $app) {
        //             $base_url =  'https://apps.cehd.umn.edu/events/Admin/XMLFeed.aspx';
        //             $query_data = $request->params();
        //             if (array_key_exists('r', $query_data) &&
        //                     array_key_exists('t', $query_data) &&
        //                     array_key_exists('q', $query_data)) {
        //                 $client_query_data = ['query' => [
        //                     'r' => $query_data['r'],
        //                     't' => $query_data['t'],
        //                     'q' => $query_data['q'],
        //                     ]
        //                 ];
        //                 $client_response = $app->_http_client->request('GET',$base_url, $client_query_data);
        //                 $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
        //             }
        //         });
        //     });

        //     $this->router->with('/programs', function() {
        //         $this->router->respond('GET', '/', function($request, $response, $service, $app) {
        //             $base_url =  'https://apps.cehd.umn.edu/Programs/RSSFeed.aspx';
        //             $query_data = $request->params();
        //             $client_query_data = ['query' => []];
        //             $valid_query_params = [
        //                 'a',
        //                 'd',
        //                 'p',
        //                 't',
        //             ];

        //             foreach ($valid_query_params as $valid_query_param)
        //             {
        //                 if (array_key_exists($valid_query_param, $query_data))
        //                 {
        //                     $client_query_data['query'][$valid_query_param] = $query_data[$valid_query_param];
        //                 }
        //             }

        //             if (!empty($client_query_data))
        //             {
        //                 $client_response = $app->_http_client->request('GET', $base_url, $client_query_data);
        //                 $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
        //             }
        //         });
        //     });
        // });
    }

    protected function _renderTemplate($template_path, $template_data)
    {
        return $this->template->render($template_path, $template_data);
    }

    public function searchRoute($request, $response, $service, $app)
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

    public function searchEndpointRoute($request, $response, $service, $app)
    {
        $search_client = new \CEHD\App\Search\Search($this->_sites);
        $query_data = $request->params();
        $results = $search_client->get($query_data);
        return $response->sendJSONResponse(json_encode($results));
    }
}
