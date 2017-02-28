<?php
namespace CEHD\App\Services;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/People/People.php';
require_once __DIR__ . '/../../../cehd/app/Response/BaseResponse.php';
require_once __DIR__ . '/../../../cehd/app/Search/Search.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Bootstrap.php';

use Klein;

class Services
{
    private $_http_client; // make this protected after moving routes that use it

    private $_search_client;

    private $_base_url;

    public $response;

    function __construct($base_url="/")
    {
        $bootstrap_helpers = new \CEHD\Helpers\Bootstrap\Bootstrap;

        $this->_http_client = $bootstrap_helpers->bootstrap_http_client([]);

        $this->_base_url = $base_url;

        $this->_search_client = new \CEHD\App\Search\Search(
            [
                "primary" => [
                    "name" => "College of Education + Human Development",
                    "url" => "www.cehd.umn.edu",
                ],
                "secondary" => [
                    "name" => "University of Minnesota",
                    "url" => "www.umn.edu",
                ]
            ]
        );

        $this->response = new \CEHD\App\Response\BaseResponse();

        $this->router = new Klein\Klein(null, $this);

        $this->router->with($this->_base_url . '/services', function() {
            /**
             * Search
             */
            $this->router->with('/search', function() {
                $this->router->respond('GET', '/', function($request, $response, $service, $app) {
                    $query_data = $request->params();
                    $results = $app->_search_client->get($query_data);
                    $response->sendJSONResponse(json_encode($results));
                });
            });

            /**
             * People Tool
             *
             * This makes a request for People Tool data, parses it to JSON and
             * sends a JSON blob back
             */
            $this->router->with('/people-tool', function() {
                /**
                 * GET handler for directory
                 */
                $this->router->respond('GET', '/', function($request, $response, $service, $app) {
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
                        $client_query_data = ['query' => $query];
                        $client_response = $app->_http_client->request('GET', $base_url, $client_query_data);
                        $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
                    }
                });

                /**
                 * GET handler for individual people
                 */
                $this->router->respond('GET', '/person/', function($request, $response, $service, $app) {
                    $query_data = $request->params();
                    $base_url =  'https://apps.cehd.umn.edu/people/admin/XMLFeed.aspx';

                    if (array_key_exists('internetId', $query_data)) {
                        $client_query_data = ['query' => [
                            'id' => $query_data['internetId'
                            ]
                        ]];
                        $client_response = $app->_http_client->request('GET', $base_url, $client_query_data);
                        $person = new \CEHD\App\People\Person($client_response->getBody());
                        $response->sendJSONResponse($person->toJSON());
                    }
                });
            });

            /**
             * News Feeds
             *
             * This makes a request to the news RSS feed, parses it to JSON and
             * sends a JSON blob back
             */
            $this->router->with('/news', function() {
                $this->router->respond('GET', '/feed/', function($request, $response, $service, $app) {
                    $base_url = "http://news.cehd.umn.edu/";
                    $query_data = $request->params();
                    if (array_key_exists('feed-path', $query_data))
                    {
                        $request_url = $base_url . $query_data['feed-path'];
                        $client_response = $app->_http_client->request('GET', $request_url);
                        $xml = $client_response->getBody();
                        $xml_obj = $response->xmlStrToObject($xml);
                        $result_array = $response->getNewsfeedArrayFromXML($xml_obj);
                        $json = json_encode($result_array);
                        $response->sendJSONResponse($json);
                    }
                });
            });

            /**
             * Events Calendar
             *
             * This makes a request to the news RSS feed, parses it to JSON and
             * sends a JSON blob back
             */
            $this->router->with('/events', function() {
                $this->router->respond('GET', '/feed/', function($request, $response, $service, $app) {
                    $base_url =  'https://apps.cehd.umn.edu/events/Admin/XMLFeed.aspx';
                    $query_data = $request->params();
                    if (array_key_exists('r', $query_data) &&
                            array_key_exists('t', $query_data) &&
                            array_key_exists('q', $query_data)) {
                        $client_query_data = ['query' => [
                            'r' => $query_data['r'],
                            't' => $query_data['t'],
                            'q' => $query_data['q'],
                            ]
                        ];
                        $client_response = $app->_http_client->request('GET',$base_url, $client_query_data);
                        $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
                    }
                });
            });

            $this->router->with('/programs', function() {
                $this->router->respond('GET', '/', function($request, $response, $service, $app) {
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
                        $client_response = $app->_http_client->request('GET', $base_url, $client_query_data);
                        $response->sendJSONResponse($response->xmlToJSON($client_response->getBody()));
                    }
                });
            });
        });
    }
}
