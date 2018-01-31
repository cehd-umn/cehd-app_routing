<?php

require_once __DIR__ . '/../cehd/app/XML/InfoSession.php';

use PHPUnit\Framework\TestCase;


class InfoSessionTest extends TestCase
{
    protected function setUp()
    {
        $this->validRSSStr = file_get_contents(__DIR__ . '/fixtures/xml/info_sessions.xml');
        $this->infoSessionInstance = new \CEHD\XML\InfoSession\InfoSessions($this->validRSSStr);
    }

    public function testCreation()
    {
        $expected_count = 4;
        $expected_link = "http://apps.cehd.umn.edu/students/infosession/UGRform.aspx?infoSessionId=4460";
        $expected_title = "Information Session for Transfer Admission to CEHD majors";
        $expected_year = "2017";
        $expected_date = "7/14/2017";
        $expected_day = "14";
        $expected_month = "7";
        $expected_full_month = "July";
        $expected_short_month = "Jul";
        $expected_timestamp = "1500026400";

        $this->assertTrue(is_array($this->infoSessionInstance->infoSessions));
        $this->assertEquals($expected_count, count($this->infoSessionInstance->infoSessions));
        $this->assertEquals($expected_link, $this->infoSessionInstance->infoSessions[0]->url);
        $this->assertEquals($expected_title, $this->infoSessionInstance->infoSessions[0]->title);
        $this->assertEquals($expected_year, $this->infoSessionInstance->infoSessions[0]->year);
        $this->assertEquals($expected_date, $this->infoSessionInstance->infoSessions[0]->date);
        $this->assertEquals($expected_day, $this->infoSessionInstance->infoSessions[0]->day);
        $this->assertEquals($expected_month, $this->infoSessionInstance->infoSessions[0]->month);
        $this->assertEquals($expected_full_month, $this->infoSessionInstance->infoSessions[0]->fullMonth);
        $this->assertEquals($expected_short_month, $this->infoSessionInstance->infoSessions[0]->shortMonth);
        $this->assertEquals($expected_timestamp, $this->infoSessionInstance->infoSessions[0]->timestamp);
    }

    public function testInvalidDate()
    {
        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/info_sessions_1900.xml');
        $session = new \CEHD\XML\InfoSession\InfoSessions($xmlStr);
        $expected_year = "1900";
        $this->assertEquals($expected_year, $session->infoSessions[0]->year);
    }
}
