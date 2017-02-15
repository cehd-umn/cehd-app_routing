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

    public function xmlStrToObject($xml_string)
    {
        return simplexml_load_string(
            $xml_string,
            null,
            LIBXML_NOCDATA);
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

    // @todo use Helper::stripDotHtml method instead
    public function htmlFilenameToURI($uri)
    {
        // strip .html (last 5 characters)
        return strtolower(substr($uri, 0, -5)) . '/';
    }

    public function redirectToURI($uri)
    {
        $this->redirect($this->htmlFilenameToURI($uri), 301)->send();
    }

    public function redirectToLowercaseURI($uri)
    {
        $this->redirect(strtolower($uri), 301)->send();
    }

    public function getNewsfeedArrayFromXML($xml)
    {
        $results = [];
        $xml->registerXPathNamespace('ns',
          'http://purl.org/rss/1.0/modules/content/');

        foreach ($xml->channel->item as $node)
        {
            $thumbnail = $node->xpath("content:thumbnail");
            $teaser = $node->xpath("content:teaser");
            $internal_title = $node->xpath("content:titleinternal");
            $link_override = $node->xpath("content:webpage");

            if (!$teaser) {
                $teaser[0] = null;
            }

            if (!$internal_title) {
                $internal_title[0] = null;
            }

            if (!$link_override) {
                $link_override[0] = null;
            }

            array_push($results, [
                "title" => (string) $node->title,
                "description" => (string) $node->description,
                "link" => (string) $node->link,
                "thumbnail" => (string) $thumbnail[0],
                "teaser" => (string) $teaser[0],
                "title_override" => (string) $internal_title[0],
                "link_override" => (string) $link_override[0],
            ]);
        }
        return $results;
    }

    public function peopleRedirect()
    {
    }
}
