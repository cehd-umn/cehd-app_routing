<?php
namespace CEHD\App\Response;

require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * Custom Response class with extra methods
 */
class BaseResponse extends \Klein\Response {

    public function xmlToJSON($xml_string) {
        $xml = simplexml_load_string($xml_string);
        return json_encode($xml);
    }

    public function sendJSONResponse($json) {
        $this->header('Content-Type', 'application/json');
        $this->body($json);
        $this->send();
    }

}
