<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 * A class for handling news Google Search Appliance results.
 *
 */

namespace CEHD\XML\Search;
require_once __DIR__ . '/../../../cehd/app/XML/XML.php';

/**
 * Result class
 *
 * @extends XML
 */
class Result extends \CEHD\App\XML\XML
{
    /**
     * Class properties
     */

    /**
     * Result description
     *
     * @type string
     */
    public $description;

    /**
     * Result link
     *
     * @type string
     */
    public $link;

    /**
     * Result title
     *
     * @type string
     */
    public $title;

    function __construct($xml)
    {
        $this->description = $xml->S;

        $this->link = $xml->U;

        $this->title = $xml->T;
    }
}

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
     * The list of sites to search
     *
     * @type array
     */
    public $results = [];

    /**
     * Constructor
     *
     * @param string $xml - A string containing valid XML from the Google
     * Search Appliance
     *
     */
    function __construct(string $xml)
    {
        $xml = $this->xmlStringToObject($xml);

        if ($xml->RES->R)
        {
            foreach ($xml->RES->R as $result)
            {
                array_push($this->results, new Result($result));
            }
        }
    }
}
