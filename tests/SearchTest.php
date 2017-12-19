<?php

require_once __DIR__ . '/../cehd/app/XML/Search.php';

use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    private $search;

    protected function setUp()
    {
        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/search.xml');
        $this->search = new \CEHD\XML\Search\Search($xmlStr);
    }

    public function testCreate()
    {
        $count = count($this->search->results);
        $expected_title = "Teaching License Process | CEHD | UMN";
        $expected_description = "<b>...</b> Teacher <b>licensure</b> process. Getting a Minnesota teaching license has<br> many steps, but our <b>licensure</b> team is here to help. <b>...</b>";
        $expected_link = "http://www.cehd.umn.edu/teaching/license/";
        $expected_suggestion_link = "http://www.cehd.umn.edu/teaching/additional/";

        $this->assertEquals(10, $count);
        $this->assertEquals($expected_title, $this->search->results[0]->title);
        $this->assertEquals($expected_description, $this->search->results[0]->description);
        $this->assertEquals($expected_link, $this->search->results[0]->link);
        $this->assertEquals($expected_suggestion_link, $this->search->keyMatch->link);
    }
}
