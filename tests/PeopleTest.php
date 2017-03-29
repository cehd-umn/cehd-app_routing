<?php

require_once __DIR__ . '/../cehd/app/People/People.php';

use PHPUnit\Framework\TestCase;


class PeopleTest extends TestCase
{
    protected $people_obj_multiple_presentations;

    protected $people_obj_single_presentation;

    protected $people_multiple_from_tag;

    protected function setUp()
    {
        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/person_caberger.xml');

        $this->people_obj_multiple_presentations = new \CEHD\App\People\People($xmlStr);

        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/person_kwahl.xml');

        $this->people_obj_single_presentation = new \CEHD\App\People\People($xmlStr);

        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/people_tag.xml');

        $this->people_multiple_from_tag = new \CEHD\App\People\People($xmlStr);

        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/person_christ.xml');

        $this->people_christ = new \CEHD\App\People\People($xmlStr);
    }

    public function testCreate()
    {
        $this->assertEquals(1, $this->people_obj_multiple_presentations->get_count());
    }

    public function testPresentationIsArray()
    {
        $this->assertTrue(is_array($this->people_obj_single_presentation->people[0]->presentations));
        $this->assertEquals(1, count($this->people_obj_single_presentation->people[0]->presentations));
        $this->assertTrue(is_array($this->people_obj_multiple_presentations->people[0]->presentations));
        $this->assertEquals(18, count($this->people_obj_multiple_presentations->people[0]->presentations));
    }

    public function testToJSON()
    {
        $this->assertTrue(is_string($this->people_obj_single_presentation->people[0]->toJSON()));
    }

    public function testMultiplePeople() {
        $this->assertEquals(10, count($this->people_multiple_from_tag->people));
    }

    public function testJobs()
    {
        $this->assertEquals(3, count($this->people_multiple_from_tag->people[0]->jobs));
        $this->assertEquals("Associate Professor", $this->people_multiple_from_tag->people[0]->jobs[0]->description);
        $this->assertEquals("Codding,Robin Susan", $this->people_multiple_from_tag->people[0]->name);
    }

    public function testTags()
    {
        $this->assertEquals("Directory", $this->people_multiple_from_tag->people[0]->tags[0]->description);
        $this->assertEquals("225", $this->people_multiple_from_tag->people[0]->tags[0]->id);
        $this->assertEquals("tg", $this->people_multiple_from_tag->people[0]->tags[0]->query_key);
    }

    public function testTagGroups()
    {
        $this->assertEquals("Educational Psychology", $this->people_multiple_from_tag->people[0]->tagGroups[0]->description);
        $this->assertEquals("EdPsych", $this->people_multiple_from_tag->people[0]->tagGroups[0]->abbreviation);
        $this->assertEquals("38", $this->people_multiple_from_tag->people[0]->tagGroups[0]->id);
        $this->assertEquals("en", $this->people_multiple_from_tag->people[0]->tagGroups[0]->query_key);
    }

    public function testChrist()
    {
        $this->assertEquals("Professor", $this->people_christ->people[0]->jobs[0]->description);
    }
}
