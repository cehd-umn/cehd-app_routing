<?php

namespace CEHD\App\XML;
require_once __DIR__ . '/../../../vendor/autoload.php';
require_once __DIR__ . '/../../../cehd/app/helpers/Bootstrap.php';

class XML
{
    protected $_http_client;

    function __construct()
    {
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

    public function toJSON()
    {
        return json_encode($this, true);
    }
}
