<?php

namespace CEHD\App\XML;
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Bootstrap.php';

class XML
{
    protected $_http_client;

    function __construct()
    {
        // @todo while putting an http client here is sometimes convenient,
        // it's probably not good design
        $bootstrap_helper = new \CEHD\Helpers\Bootstrap\Bootstrap;
        $this->_http_client = $bootstrap_helper::bootstrap_http_client([]);
    }

    protected function _xmlStringToObject($xml_string)
    {
        return simplexml_load_string(
            $xml_string,
            null,
            LIBXML_NOCDATA);
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
