<?php

require_once __DIR__ . '/../cehd/app/XML/Event.php';

use PHPUnit\Framework\TestCase;


class EventTest extends TestCase
{
    protected $eventInstance;

    protected function setUp()
    {
        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/events.xml');
        $this->eventInstance = new \CEHD\XML\Event\Events($xmlStr);
    }

    public function testCreate()
    {
        $count = count($this->eventInstance->events);
        $this->assertEquals(10, $count);
        $this->assertEquals($count, $this->eventInstance->get_count());
        $this->assertEquals("6949", $this->eventInstance->events[0]->id);
        $this->assertEquals("3/31/2017", $this->eventInstance->events[0]->date);
        $this->assertEquals("11:00 AM", $this->eventInstance->events[0]->startTime);
        $this->assertEquals("1:00 PM", $this->eventInstance->events[0]->endTime);
    }

    public function testEventDay()
    {
        $expected = "31";
        $this->assertEquals($expected, $this->eventInstance->events[0]->day());
    }

    public function testEventMonth()
    {
        $expected = "3";
        $this->assertEquals($expected, $this->eventInstance->events[0]->month());
    }

    public function testEventFullMonth()
    {
        $expected = "March";
        $this->assertEquals($expected, $this->eventInstance->events[0]->fullMonth());
    }

    public function testEventShortMonth()
    {
        $expected = "Mar";
        $this->assertEquals($expected, $this->eventInstance->events[0]->shortMonth());
    }

    public function testEventYear()
    {
        $expected = "2017";
        $this->assertEquals($expected, $this->eventInstance->events[0]->year());
    }

    public function testEventTimestamp()
    {
        $expected = 1490976000;
        $this->assertEquals($expected, $this->eventInstance->events[0]->timestamp());

        // Test w/ the end date
        $expected = 1490983200;
        $this->assertEquals($expected, $this->eventInstance->events[0]->timestamp(true));
    }
}
