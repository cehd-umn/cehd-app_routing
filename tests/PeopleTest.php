<?php

require_once __DIR__ . '/../cehd/app/People/People.php';

use PHPUnit\Framework\TestCase;


class PeopleTest extends TestCase
{
    protected $person_multiple_presentations;

    protected $person_single_presentation;

    protected function setUp()
    {
        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/person_caberger.xml');

        $this->person_multiple_presentations = new \CEHD\App\People\Person($xmlStr);

        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/person_kwahl.xml');

        $this->person_single_presentation = new \CEHD\App\People\Person($xmlStr);
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->person_multiple_presentations->count);
    }

    public function testPresentationIsArray()
    {
        $this->assertTrue(is_array($this->person_single_presentation->presentations));
        $this->assertEquals(1, count($this->person_single_presentation->presentations));
        $this->assertTrue(is_array($this->person_multiple_presentations->presentations));
        $this->assertEquals(18, count($this->person_multiple_presentations->presentations));
    }

    public function testToJSON()
    {
        $this->assertTrue(is_string($this->person_single_presentation->toJSON()));
    }
}
