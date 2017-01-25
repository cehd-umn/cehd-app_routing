<?php

namespace CEHD\App\Search;
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Bootstrap.php';

class Search
{
    private $_sites = [
        "primary" => [
            "name" => "Educational Psychology",
            "url" => "www.cehd.umn.edu/edpsych",
        ],
        "secondary" => [
            "name" => "College of Education + Human Development",
            "url" => "www.cehd.umn.edu",
        ],
        "tertiary" => [
            "name" => "University of Minnesota",
            "url" => "www.umn.edu",
        ]
    ];

    private $_http_client;

    private $_base_url = "http://google.umn.edu/search";

    function __construct()
    {
        $bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;
        $this->_http_client = $bootstrap_helper::bootstrap_http_client([]);
    }

    private function _validateQueryParams($params)
    {
        return array_key_exists('q', $params);
    }

    private function _getStart($params)
    {
        if (array_key_exists('start', $params))
        {
            return $params['start'];
        }

        return 0;
    }

    private function _getSites($params)
    {
        if (array_key_exists('sites', $params))
        {
            return $params['sites'];
        }

        return ["primary", "secondary", "tertiary"];
    }

    private function _getNum($params)
    {
        if (array_key_exists('num', $params))
        {
            return $params['num'];
        }

        return 10;
    }

    private function _xmlStringToObject($xml_string)
    {
        return simplexml_load_string(
            $xml_string,
            null,
            LIBXML_NOCDATA);
    }

    private function _getSearchResultsFromXMLObject($obj)
    {
        $results = [];
        $i = 0;

        if ($obj->RES->R)
        {
            foreach ($obj->RES->R as $result_set)
            {
                $results[$i] = [
                    "title" => (string) $result_set->T,
                    "description" => (string) $result_set->S,
                    "link" => (string) $result_set->U,
                ];
                $i++;
            }
        }

        return $results;
    }

    private function _get($query_params, $site)
    {
        $start = $this->_getStart($query_params);

        $num = $this->_getNum($query_params);

        $query = str_replace(" ", "+", urldecode($query_params["q"]));

        $client_query_data = [
            'query' => [
                'q' => $query,
                'start' => $start,
                'as_sitesearch' => $site,
                'num' => $num,
            ]
        ];

        $client_response = $this->_http_client->request(
            "GET", $this->_base_url, $client_query_data);

        $xml_obj = $this->_xmlStringToObject(
            $client_response->getBody());

        return $this->_getSearchResultsFromXMLObject($xml_obj);
    }

    public function get($query_params)
    {
        $results = [];

        if ($this->_validateQueryParams($query_params))
        {
            $site_keys = $this->_getSites($query_params);

            foreach ($site_keys as $site_key)
            {
                $result =$this->_get(
                    $query_params, $this->_sites[$site_key]["url"]);

                $results[$site_key] = [
                    "name" => $this->_sites[$site_key]["name"],
                    "results" => $result,
                ];
            };
        }
        return $results;
    }
}
