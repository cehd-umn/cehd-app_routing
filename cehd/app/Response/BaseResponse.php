<?php
namespace CEHD\App\Response;

require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * Custom Response class with extra methods
 */
class BaseResponse extends \Klein\Response
{

    public function xmlToJSON($xml_string)
    {
        $xml = simplexml_load_string($xml_string);
        return json_encode($xml);
    }

    public function xmlStrToObject($xml_string)
    {
        return simplexml_load_string($xml_string);
    }

    public function sendJSONResponse($json)
    {
        $this->header('Content-Type', 'application/json');
        $this->body($json);
        $this->send();
    }

    public function templateNameFromURI($uri)
    {
        // trim the opening and closing slashes
        $trimmed = ltrim($uri, '/');
        $trimmed = rtrim($trimmed, '/');

        // turn the uri into an array
        $path_array = explode('/', $trimmed);

        // get the last element and concatenate the html extension
        return strtolower(array_pop($path_array)) . '.html';
    }

    // @todo this should probably be a request method
    public function htmlFilenameToURI($uri)
    {
        // strip .html (last 5 characters)
        return strtolower(substr($uri, 0, -5)) . '/';
    }

    public function redirectToURI($uri)
    {
        $this->redirect($this->htmlFilenameToURI($uri), 301)->send();
    }

    public function getSearchResponse($query, $site, $client, $num=10)
    {
        $base_url = "http://google.umn.edu/search";
        $client_query_data = [
            'query' => [
                'q' => $query,
                'as_sitesearch' => $site,
                'num' => $num,
            ]
        ];
        return $client->request('GET', $base_url, $client_query_data);
    }

    // @todo This method is specific to Google XML objects, change the name?
    public function getSearchResultsFromXMLObject($obj)
    {
        $results = [];
        $i = 0;

        foreach ($obj->RES->R as $result_set)
        {
            $results[$i] = [
                "title" => $result_set->T,
                "description" => $result_set->S,
                "link" => $result_set->U,
            ];
            $i++;
        }

        return $results;
    }
}
