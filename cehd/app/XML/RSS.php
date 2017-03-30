<?php

namespace CEHD\App\XML\RSS;
require_once __DIR__ . '/XML.php';

class RSS extends \CEHD\App\XML\XML
{
    public function getContentAttr($node, $attr_name, $default=null)
    {
        $node->registerXPathNamespace('ns',
          'http://purl.org/rss/1.0/modules/content/');

        $xpath = "content:" . $attr_name;
        $value = $node->xpath($xpath);

        if ($value)
        {
            return trim((string) $value[0]);
        }
        return $default;
    }
}
