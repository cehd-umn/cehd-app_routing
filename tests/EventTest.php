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
        $this->assertEquals("March", $this->eventInstance->events[0]->fullMonth);
        $this->assertEquals("Mar", $this->eventInstance->events[0]->shortMonth);
        $this->assertEquals("Friday", $this->eventInstance->events[0]->dayOfWeek);
        $this->assertEquals("Related event", $this->eventInstance->events[0]->relatedEventTitle);
        $this->assertEquals("Related event url", $this->eventInstance->events[0]->relatedEventURL);
    }

    public function testEventDay()
    {
        $expected = "31";
        $this->assertEquals($expected, $this->eventInstance->events[0]->getDay());
    }

    public function testEventMonth()
    {
        $expected = "3";
        $this->assertEquals($expected, $this->eventInstance->events[0]->getMonth());
    }

    public function testEventFullMonth()
    {
        $expected = "March";
        $this->assertEquals($expected, $this->eventInstance->events[0]->getFullMonth());
    }

    public function testEventShortMonth()
    {
        $expected = "Mar";
        $this->assertEquals($expected, $this->eventInstance->events[0]->getShortMonth());
    }

    public function testEventYear()
    {
        $expected = "2017";
        $this->assertEquals($expected, $this->eventInstance->events[0]->getYear());
    }

    public function testEventTimestamp()
    {
        $expected = 1490976000;
        $this->assertTrue(is_int($this->eventInstance->events[0]->getTimestamp()));
    }

    public function testEventGetStartHour()
    {
        $expected = "11";
        $this->assertEquals($expected, $this->eventInstance->events[0]->getStartHour());
    }

    public function testEventGetEndHour()
    {
        $expected = "01";
        $this->assertEquals($expected, $this->eventInstance->events[0]->getEndHour());
    }
    public function testEventGetEndMinute()
    {
        $expected = "00";
        $this->assertEquals($expected, $this->eventInstance->events[0]->getEndMinute());
    }

    public function testEventGetStartMinute()
    {
        $expected = "00";
        $this->assertEquals($expected, $this->eventInstance->events[0]->getStartMinute());
    }

    public function testNoEventsReturned()
    {
        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/events_empty.xml');
        $eventInstance = new \CEHD\XML\Event\Events($xmlStr);
        $this->assertEmpty($eventInstance->events);
    }
}
