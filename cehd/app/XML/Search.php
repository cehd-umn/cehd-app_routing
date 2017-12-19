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
        $this->description = $this->getFromProperty($xml->S);

        $this->link = $this->getFromProperty($xml->U);

        $this->title = $this->getFromProperty($xml->T);
    }
}

class KeyMatch extends \CEHD\App\XML\XML
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

    function __construct(string $description, string $link)
    {
        $this->description = $description;

        $this->link = $link;
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
     * The list of sites to search
     *
     * @type KeyMatch
     */
    public $keyMatch;

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

        if ($xml->RES->R) {
            foreach ($xml->RES->R as $result)
            {
                array_push($this->results, new Result($result));
            }
        }

        if ($xml->GM) {
            $this->keyMatch = new KeyMatch($this->getFromProperty(
                $xml->GM->GD), $this->getFromProperty($xml->GM->GL));
        }
    }
}
