<?php

require_once __DIR__ . '/../cehd/app/XML/RSS.php';

use PHPUnit\Framework\TestCase;


class RSSTest extends TestCase
{
    protected function setUp()
    {
        $this->validRSSStr = file_get_contents(__DIR__ . '/fixtures/xml/newsfeed.xml');
        $this->rssInstance = new \CEHD\App\XML\RSS\RSS();
        $this->obj = $this->rssInstance->xmlStringToObject($this->validRSSStr);
    }

    public function testGetContentAttr()
    {
        $thumbnail = $this->rssInstance->getContentAttr($this->obj->channel->item[0], "thumbnail");
        $expected = "http://news.cehd.umn.edu/wp-content/uploads/2016/09/kendeou_2.jpg";
        $this->assertEquals($expected, $thumbnail);
    }
}
