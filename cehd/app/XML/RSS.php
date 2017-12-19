<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 * A class for consistently handling RSS.
 *
 */

namespace CEHD\App\XML\RSS;

require_once __DIR__ . '/XML.php';

/**
 * RSS class
 *
 * @extends XML
 */
class RSS extends \CEHD\App\XML\XML
{

    /**
     * Class methods
     */

    /**
     * Gets the value of a content XPath namespace attribute
     *
     * @param object $node - An XML object from an RSS feed
     * @param string $attr_name - The name of the attribute we want. I.e., "content:link_override";
     * @param mixed $default - The default value to return if the attribute is not present
     */
    public function getContentAttr($node, $attr_name, $default=null)
    {
        $node->registerXPathNamespace(
            'ns',
          'http://purl.org/rss/1.0/modules/content/'
        );

        $xpath = "content:" . $attr_name;
        $value = $node->xpath($xpath);

        if ($value) {
            return trim((string) $value[0]);
        }
        return $default;
    }
}
