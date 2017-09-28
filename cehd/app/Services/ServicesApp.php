<?php
namespace CEHD\Services;

require_once __DIR__ . "/../../../cehd/app/Base.php";
require_once __DIR__ . "/../../../cehd/app/XML/Event.php";
require_once __DIR__ . "/../../../cehd/app/XML/InfoSession.php";
require_once __DIR__ . '/../../../cehd/app/XML/News.php';
require_once __DIR__ . '/../../../cehd/app/XML/People.php';
require_once __DIR__ . '/../../../cehd/app/XML/Search.php';
require_once __DIR__ . '/../../../cehd/app/Validator/Validator.php';

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \CEHD\App\BaseClass;
use \CEHD\XML\Event\Events;
use \CEHD\XML\InfoSession\InfoSessions;
use \CEHD\XML\News\News;
use \CEHD\XML\People\People;
use \CEHD\XML\Search\Search;
use \CEHD\Validator\Validator;

class InvalidParameterException extends \Exception
{
}

class MissingParameterException extends \Exception
{
}

class ServicesApp extends BaseClass
{
    public function eventsHandler(Request $request, Response $response)
    {
        $base_url =  "https://apps.cehd.umn.edu/events/Admin/XMLFeed.aspx";
        $query_data = $request->getQueryParams();
        $events = [];

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
            $client_response = $http_client->request("GET", $base_url, $client_query_data);
            $events =  new Events($client_response->getBody());
        }

        return $response->withJson($events->events);
    }

    public function eventHandler(Request $request, Response $response)
    {
        $query_data = $request->getQueryParams();

        if (array_key_exists("z", $query_data)) {
            // @todo review this
            return $response->withJson($this->getEventJSON($query_data["z"], $response));
        }
        return $response->withJson([]);
    }

    public function getEventJSON($event_id, Response $response)
    {
        return $response->withJson($this->getEvent($event_id));
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
        $events =  new Events($client_response->getBody());
        return $events->events[0];
    }

    public function infoSessionsHandler(Request $request, Response $response)
    {
        $base_url = "https://apps.cehd.umn.edu/students/infosession/admin/RSSFeed.aspx";
        $query_data = $request->getQueryParams();

        if (array_key_exists("code", $query_data)) {
            $http_client = $this->bootstrapHTTPClient([]);
            $client_query_data = ["query" => [
                "code" => $query_data["code"],
                ]
            ];

            $client_response = $http_client->request("GET", $base_url, $client_query_data);
            $xml = $client_response->getBody();
            $infoSessions = new InfoSessions($xml);
            return $response->withJson($infoSessions->infoSessions);
        }
        return $response->withJson([]);
    }

    public function newsFeedHandler(Request $request, Response $response)
    {
        $base_url = "http://news.cehd.umn.edu/";
        $query_data = $request->getQueryParams();

        if (array_key_exists("feed_url", $query_data) && !empty($query_data["feed_url"])) {
            $base_url = $query_data["feed_url"];
            $client_query_data = ["query" => [
                "feed_url" => $query_data["feed_url"],
                ]
            ];
        }

        if (array_key_exists("feed-path", $query_data)) {
            $http_client = $this->bootstrapHTTPClient([]);
            $request_url = $base_url . $query_data["feed-path"];
            $client_query_data = ["query" => [
                "feed-path" => $query_data["feed-path"],
                ]
            ];
            $client_response = $http_client->request("GET", $request_url);
            $news = new News($client_response->getBody());

            if ($this->arrayContains("sortBy", $query_data) && $query_data["sortBy"] == "categoryDecade") {
                $news->sortByCategoryDecade();
            }

            return $response->withJson($news->stories);
        }
        $response->withJson([]);
    }

    public function peopleHandler(Request $request, Response $response)
    {
        $base_url =  'https://apps.cehd.umn.edu/people/admin/XMLFeed.aspx';
        $query_data = $request->getQueryParams();
        $query = [];

        /**
         * Requests that get here in IIS have lots of extraneous query params
         * tacked on. So many that the HTTP client (Guzzle) pukes because the
         * byte-size of the URL is too large. Strip out extraneous query params
         * to prevent that.
         */
        if (array_key_exists('en', $query_data)) {
            $query['en'] = $query_data['en'];
        };

        if (array_key_exists('tg', $query_data)) {
            $query['tg'] = $query_data['tg'];
        };

        if (array_key_exists('nm', $query_data)) {
            $query['nm'] = $query_data['nm'];
        };

        if (
            array_key_exists('en', $query_data) ||
            array_key_exists('tg', $query_data) ||
            array_key_exists('nm', $query_data)) {
            $http_client = $this->bootstrapHTTPClient([]);
            $client_query_data = ['query' => $query];
            $client_response = $http_client->request('GET', $base_url, $client_query_data);
            $people_obj = new People($client_response->getBody());
            return $response->withJson($people_obj->people);
        }
        return $response->withJson([]);
    }

    public function personHandler(Request $request, Response $response)
    {
        $query_data = $request->getQueryParams();
        $base_url =  'https://apps.cehd.umn.edu/people/admin/XMLFeed.aspx';

        if (array_key_exists('internetId', $query_data)) {
            $http_client = $this->bootstrapHTTPClient([]);
            $client_query_data = ['query' => [
                'id' => $query_data['internetId'
                ]
            ]];
            $client_response = $http_client->request('GET', $base_url, $client_query_data);
            $people_obj = new People($client_response->getBody());

            /**
             * People::people is always an array for consistency. Since we're
             * targeting an ID here, it's mostly harmless to just return the
             * first one. (There should only be one.)
             */
            return $response->withJson($people_obj->people[0]);
        }
        return $response->withJson([]);
    }

    public function programsHandler(Request $request, Response $response)
    {
        $base_url =  'https://apps.cehd.umn.edu/Programs/RSSFeed.aspx';
        $query_data = $request->getQueryParams();
        $client_query_data = ['query' => []];
        $valid_query_params = [
            'a',
            'd',
            'p',
            't',
        ];

        foreach ($valid_query_params as $valid_query_param) {
            if (array_key_exists($valid_query_param, $query_data)) {
                $client_query_data['query'][$valid_query_param] = $query_data[$valid_query_param];
            }
        }

        if (!empty($client_query_data)) {
            $http_client = $this->bootstrapHTTPClient([]);
            $client_response = $http_client->request('GET', $base_url, $client_query_data);

            // @todo create a programs XML model like the others
            $xml = simplexml_load_string(
                $client_response->getBody(),
                null,
                LIBXML_NOCDATA
            );
            return $response->withJson($xml);
        }
        return $response->withJson([]);
    }

    public function isRequired(array $param)
    {
        return $param["required"];
    }

    private function _validateRequestHasRequiredParams($required, $received)
    {
        $missing_params = array_diff(array_keys($required), $received);
        if (count($missing_params) > 0) {
            $msg = "";
            foreach ($missing_params as $key) {
                $msg = $msg . "Missing parameter: " . $key . "\n";
                $msg = $msg . $key . " info: " . $required[$key]["description"] . " and should be a " . $required[$key]["type"] . "\n";
            }
            throw new MissingParameterException($msg);
            return false;
        }
        return true;
    }

    public function searchHandler(Request $request, Response $response)
    {
        $base_url = "http://google.umn.edu/search";
        $results = [];
        $http_client = $this->bootstrapHTTPClient([]);
        $query_data = (array) $request->getQueryParams();
        $valid_query_params = [
            "client" => [
                "description" => "client is the short name of a GSA organization. E.g. tc_education",
                "required" => true,
                "type" => "string"
            ],
            "in_url" => [
                "description" => "The specific url to search. E.g., www.cehd.umn.edu",
                "required" => false,
                "type" => "string"
            ],
            "num" => [
                "description" => "The number of results to return",
                "required" => true,
                "type" => "integer"
            ],
            "q" => [
                "description" => "A URI encoded search string",
                "required" => true,
                "type" => "string"
            ],
            "site" => [
                "description" => "The GSA collection name. E.g., tc_education_students",
                "required" => true,
                "type" => "string"
            ],
            "start" => [
                "description" => "Index of where to start in the results.",
                "required" => true,
                "type" => "integer"
            ],
        ];

        $required_params = array_filter($valid_query_params, [$this, "isRequired"]);

        try  {
            $this->_validateRequestHasRequiredParams($required_params, array_keys($query_data));
        } catch (MissingParameterException $e) {
            $newResponse = $response->withStatus(400);
            $newResponse->getBody()->write($e->getMessage());
            return $newResponse;
        }

        $client_query_data = ["query" => []];

        // strips out any unnecessary query params
        foreach (array_keys($valid_query_params) as $name) {
            if ($this->arrayContains($name, $query_data)) {
                $client_query_data["query"][$name] = $query_data[$name];
            }
        }

        $client_response = $http_client->request("GET", $base_url,
            $client_query_data);
        $resultSet = new Search($client_response->getBody());
        return $response->withJson($resultSet);
    }
}
