<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 * A class for handling XML.
 *
 */

namespace CEHD\App\XML;

require_once __DIR__ . "/../../../cehd/app/Base.php";
require_once __DIR__ . "/../../../vendor/autoload.php";

/**
 * XML class
 *
 * @extends BaseClass
 */
class XML extends \CEHD\App\BaseClass
{
    /**
     * Class methods
     */

    /**
     * Parses an XML string into a SimpleXMLElement object
     *
     * @param string $xml
     * @return object SimpleXMLElement $xml - An object representation of XML
     */
    public function xmlStringToObject($xml)
    {
        // Make errors internal
        libxml_use_internal_errors(true);
        if (trim($xml) === "") {
            return null;
        } else {
            $obj = simplexml_load_string($xml, null, LIBXML_NOCDATA);
            if (!$obj) {
                // Throw exceptions if the XML was invalid
                $xml_errors = libxml_get_errors();

                $errors = $this->parseXMLErrors($xml_errors);

                foreach ($errors as $msg) {
                    throw new \Exception($msg);
                }
            }
            return $obj;
        }
    }

    /**
     * Casts to string and trims whitespace
     *
     * @param mixed $property
     * @return string
     */
    public function toString($property)
    {
        return trim((string) $property);
    }

    /**
     * Casts to string and trims whitespace; returns null if the string is
     * empty
     *
     * @param mixed $property
     * @return mixed string or null
     */
    public function getFromProperty($property)
    {
        $stringified = $this->toString($property);
        return !empty($stringified) ? $stringified : null;
    }

    /**
     * Casts string "true" or "false" to boolean
     *
     * @param mixed $property
     * @return boolean
     */
    public function getBoolFromProperty($property)
    {
        $stringified = strtolower($this->getFromProperty($property));

        switch ($stringified) {
            case "true":
                return true;
            case "false":
                return false;
            default:
                $msg = $stringified . " is not a boolean (true or false)";
                throw new \Exception($msg);
        }
    }

    /**
     * Creates a JSON representation of any object using this class
     *
     * @return string
     */
    public function toJSON()
    {
        return json_encode($this, true);
    }
}
