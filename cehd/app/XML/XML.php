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

    protected function _parseXMLError($error)
    {
        $msg = str_repeat('-', $error->column) . "^\n";

        switch ($error->level) {
            case LIBXML_ERR_WARNING:
                $msg .= "Warning " . $error->code . ": ";
                break;
            case LIBXML_ERR_ERROR:
                $msg .= "Error " . $error->code . ": ";
                break;
            case LIBXML_ERR_FATAL:
                $msg .= "Fatal Error " . $error->code .": ";
                break;
        }

        $msg .= trim($error->message) .
               "\n  Line: $error->line" .
               "\n  Column: $error->column";

        if ($error->file) {
            $msg .= "\n  File: $error->file";
        }

        return $msg;
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
                return false;
            }
        }

        return true;
    }

    // @xxx Some of these convenience methods are probably broad enough to be
    // abstracted further to a parent BaseClass
    public function toString($property)
    {
        return trim((string) $property);
    }

    public function get($obj)
    {
        $stringified = $this->toString($obj);
        return !empty($stringified) ? $stringified : null;
    }

    public function isIterable($obj)
    {
        return is_array($obj) || $obj instanceof \Traversable;
    }

    public function toJSON()
    {
        return json_encode($this, true);
    }
}
