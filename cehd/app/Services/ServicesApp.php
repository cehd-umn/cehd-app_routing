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

    public function validateSearhParam(string $name, string $type, boolean $required, $value)
    {
        $validator = new Validator(
            $name,
            $type,
            $required,
            $value
        );

        return $validator->isValidType();
    }

    public function validateSearchParmams($params)
    {
        $invalid_params = [];
        $missing_params = [];

        $valid_params = [
            [
                "name" => "client",
                "type" => "string",
                "required" => true,
            ],
            [
                "name" => "num",
                "type" => "integer",
                "required" => true,
            ],
            [
                "name" => "site",
                "type" => "string",
                "required" => true,
            ],
            [
                "name" => "start",
                "type" => "integer",
                "required" => true,
            ],
            [
                "name" => "in_url",
                "type" => "string",
                "required" => true,
            ],
            [
                "name" => "filter",
                "type" => "string",
                "required" => true,
            ],
        ];

        foreach($valid_params as $valid_param) {
            if (!$this->arrayContains($valid_param["name"])) {
                array_push($missing_params, $valid_param);
            } elseif (!$this->validateParam(
                $valid_param["name"],
                $valid_param["type"],
                $valid_param["required"],
                $params[$valid_param["name"]])) {
                array_push($invalid_params, $params[$valid_param["name"]]);
            }
        }

        return [
            "invalid" => $invalid_params,
            "missing" => $missing_params,
        ];

    }

    // Front end sends the list of sites as query data
    // This endpoint decodes those and loops over them to make requests to the GSA
    // XML from the GSA is parsed into a new Search instance
    // The Search instance data is parsed back into a JSON array with all the sites and sent back to the font end
    public function searchHandler(Request $request, Response $response)
    {
        $results = [];
        $query_data = $request->getQueryParams();

        $missing_or_invalid = $this->validateParams($query_data);

        if (!empty($missing_or_invalid["missing"]) || !empty($missing_or_invalid)) {
            // @todo throw a real exception here
            trigger_error("Missing or invalid parameters in the request");
        }

        if ($this->arrayContains("sites", $query_data)) {
            $http_client = $this->bootstrapHTTPClient([]);
            $base_url = "http://google.umn.edu/search";

            $client_response = $this->_http_client->request(
                "GET", $base_url, $query_data);

            //@xxx Check to make sure this structure works in the class.
            array_push($results, new Search($client_response->getBody()));
            return $response->withJson($results);
        }

        return $response->withJson([]);
    }
}
