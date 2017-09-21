<?php
namespace CEHD\Services;

require_once __DIR__ . "/../../../cehd/app/Base.php";
require_once __DIR__ . "/../../../cehd/app/XML/Event.php";

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;
use \CEHD\App\BaseClass;
use \CEHD\XML\Event\Events;

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

        return $response->withJson($events);
    }

    public function eventHandler(Request $request, Response $response)
    {
        $query_data = $request->getQueryParams();

        if (array_key_exists("z", $query_data)) {
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
}
