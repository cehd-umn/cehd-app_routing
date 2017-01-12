<?php
namespace CEHD\App\Response;

require_once __DIR__ . '/../../../vendor/autoload.php';

/**
 * Custom Response class with extra methods
 */
class BaseResponse extends \Klein\Response
{

    public function xmlToJSON($xml_string)
    {
        $xml = simplexml_load_string($xml_string);
        return json_encode($xml);
    }

    public function sendJSONResponse($json)
    {
        $this->header('Content-Type', 'application/json');
        $this->body($json);
        $this->send();
    }

    public function templateNameFromURI($uri)
    {
        // trim the opening and closing slashes
        $trimmed = ltrim($uri, '/');
        $trimmed = rtrim($trimmed, '/');

        // turn the uri into an array
        $path_array = explode('/', $trimmed);

        // get the last element and concatenate the html extension
        return strtolower(array_pop($path_array)) . '.html';
    }

    // @todo this should probably be a request method
    public function htmlFilenameToURI($uri)
    {
        // strip .html (last 5 characters)
        return strtolower(substr($uri, 0, -5)) . '/';
    }

    public function redirectToURI($uri)
    {
        $this->redirect($this->htmlFilenameToURI($uri), 301)->send();
    }
}
