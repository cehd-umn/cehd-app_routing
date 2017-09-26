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
        $expected_title = "<b>Brain</b> imaging | AHC Research Services - University of <b>...</b>";
        $expected_description = "<b>...</b> You are here. Home ; <b>Brain</b> imaging. ▾ Section sub-menu ▾. <b>Brain</b> imaging.<br> Category: Facilities and equipment. University Imaging Centers. <b>...</b>";
        $expected_link = "https://www.researchservices.umn.edu/auto-created-vocabulary/brain-imaging";

        $this->assertEquals(10, $count);
        $this->assertEquals($expected_title, $this->search->results[0]->title);
        $this->assertEquals($expected_description, $this->search->results[0]->description);
        $this->assertEquals($expected_link, $this->search->results[0]->link);
    }
}
