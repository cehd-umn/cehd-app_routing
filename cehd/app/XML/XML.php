<?php

namespace CEHD\App\XML;
require_once __DIR__ . "/../../../cehd/app/Base.php";
require_once __DIR__ . "/../../../vendor/autoload.php";

class XML extends \CEHD\App\BaseClass
{
    protected $_http_client;

    function __construct()
    {
        // @todo while putting an http client here is sometimes convenient,
        // it's probably not good design
        $this->_http_client = $this->bootstrapHTTPClient([]);
    }

    protected function _xmlStringToObject($xml_string)
    {
        if ($this->validateXML($xml_string))
        {
            return simplexml_load_string($xml_string, null, LIBXML_NOCDATA);
        }
    }

    // @todo get rid of the protected method in favor of this public one
    public function xmlStringToObject($xml_string)
    {
        return $this->_xmlStringToObject($xml_string);
    }

    public function validateXML($xml_string)
    {
        libxml_use_internal_errors(true);
        if (trim($xml_string) === "")
        {
            return false;
        } else {
            $doc = simplexml_load_string($xml_string, null, LIBXML_NOCDATA);
            if (!$doc)
            {
                $xml_errors = libxml_get_errors();

                $this->_errors = $this->parseXMLErrors($xml_errors);
                return false;
            }
        }

        libxml_clear_errors();

        return true;
    }

    public function toString($property)
    {
        return trim((string) $property);
    }

    // The distinction here from "get" is that "get" can be called directly on
    // any object created from a class that extends XML. I.e.,
    // $instance->get("property_name"); This method is meant to work on generic
    // XML objects created from simplexml_load_string().
    public function getFromProperty($property)
    {
        $stringified = $this->toString($property);
        return !empty($stringified) ? $stringified : null;
    }

    public function toJSON()
    {
        return json_encode($this, true);
    }
}
