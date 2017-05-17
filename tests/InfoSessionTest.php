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
        $expected_count = 2;
        $expected_url = "http://apps.cehd.umn.edu/students/infosession/UGRform.aspx?infoSessionId=4444";
        $expected_title = "Information Session for Transfer Admission to CEHD majors";

        $this->assertTrue(is_array($this->infoSessionInstance->infoSessions));
        $this->assertEquals($expected_count, count($this->infoSessionInstance->infoSessions));
        $this->assertEquals($expected_url, $this->infoSessionInstance->infoSessions[0]->url);
        $this->assertEquals($expected_title, $this->infoSessionInstance->infoSessions[0]->title);
    }
}
