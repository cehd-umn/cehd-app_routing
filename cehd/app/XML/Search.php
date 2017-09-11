<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 * A class for handling news Google Search Appliance requests and results.
 *
 */

namespace CEHD\App\Search;

require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/XML/XML.php';

/**
 * Search class
 *
 * @extends XML
 */
class Search extends \CEHD\App\XML\XML
{
    /**
     * Class properties
     */

    /**
     * Base uri for the search appliance
     *
     * @type string
     */
    private $_base_url = "http://google.umn.edu/search";

    /**
     * The HTTP client for handling requests to the search appliance
     *
     * @type object Guzzle
     */
    private $_http_client;

    /**
     * The list of sites to search
     *
     * @type array
     */
    private $_sites;

    /**
     * Constructor
     *
     * @param array $sites - an associative array of sites to search
     *
     * <code>
     * $sites = [
     *     "primary" => [
     *           "name" => "Educational Psychology",
     *           "url" => "www.cehd.umn.edu/edpsych/",
     *       ],
     *       "secondary" => [
     *           "name" => "College of Education + Human Development",
     *           "url" => "www.cehd.umn.edu",
     *       ],
     *       "tertiary" => [
     *           "name" => "University of Minnesota",
     *           "url" => "www.umn.edu",
     *     ]
     * ];
     *
     * $search_client = new \CEHD\App\Search\Search($sites);
     * </code>
     *
     */
    public function __construct($sites)
    {
        $this->_http_client = $this->bootstrapHTTPClient([]);
        $this->_sites = $sites;
    }

    /**
     * Validates query params
     *
     * @param array $params - An array of key/value pairs representing the
     * query parameters in a request
     * @return boolean
     */
    private function _validateQueryParams($params)
    {
        return array_key_exists('q', $params);
    }

    /**
     * Gets the start value from an array of query params; Return 0 if the
     * "start" query param is not present
     *
     * @param array $params - An array of key/value pairs representing the
     * query parameters in a request
     * @return mixed - int or string
     */
    private function _getStart($params)
    {
        if (array_key_exists('start', $params)) {
            return $params['start'];
        }

        return 0;
    }

    /**
     * Gets the sites to search from query params
     *
     * @param array $params - An array of key/value pairs representing the
     * query parameters in a request
     * @return array
     */
    private function _getSites($params)
    {
        if (array_key_exists('sites', $params)) {
            return $params['sites'];
        }

        return ["primary"];
    }

    /**
     * Gets the number of results to return from query params
     *
     * @param array $params - An array of key/value pairs representing the
     * query parameters in a request
     * @return mixed - string or int
     */
    private function _getNum($params)
    {
        if (array_key_exists('num', $params)) {
            return $params['num'];
        }

        return 10;
    }

    /**
     * Parses a SimpleXMLElement object and puts search results into an array
     *
     * @param object SimpleXMLElement $obj - An object representation of XML
     * @return array - An associative array of the search results
     */
    private function _getSearchResultsFromXMLObject($obj)
    {
        $results = [];
        $i = 0;

        if ($obj->RES->R) {
            foreach ($obj->RES->R as $result_set) {
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

    /**
     * Performs an HTTP GET request to the Google Search Appliance and puts the
     * results, which are XML, into an associative array.
     *
     * @param array $query_params - An array of key/value pairs representing
     * the query parameters in a request
     * @param string $site - The value for the as_sitesearch parameter in the
     * request to the search appliance
     * @return array - An associative array of the search results
     */
    private function _get($query_params, $url, $client, $site)
    {
        $start = $this->_getStart($query_params);

        $num = $this->_getNum($query_params);

        $query = str_replace(" ", "+", urldecode($query_params["q"]));

        $client_query_data = [
            'query' => [
                'q' => $query,
                'start' => $start,
                'in_url' => $url,
                'site' => $site,
                'client' => $client,
                'num' => $num,
            ]
        ];

        $client_response = $this->_http_client->request(
            "GET",
            $this->_base_url,
            $client_query_data
        );

        $xml_obj = $this->xmlStringToObject($client_response->getBody());

        return $this->_getSearchResultsFromXMLObject($xml_obj);
    }

    /**
     * Performs HTTP GET requests to the Google Search Appliance
     *
     * @param array $query_params - An array of key/value pairs representing
     * the query parameters in a request
     * @return array - An associative array of the search results
     */
    public function getResults($query_params)
    {
        $results = [];

        if ($this->_validateQueryParams($query_params)) {
            $site_keys = $this->_getSites($query_params);

            foreach ($site_keys as $site_key) {
                $result =$this->_get(
                    $query_params,
                    $this->_sites[$site_key]["url"],
                    $this->_sites[$site_key]["client"],
                    $this->_sites[$site_key]["site"]
                );

                $results[$site_key] = [
                    "name" => $this->_sites[$site_key]["name"],
                    "results" => $result,
                ];
            };
        }
        return $results;
    }
}
