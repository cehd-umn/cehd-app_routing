<?php

require_once __DIR__ . '/../cehd/app/XML/XML.php';

use PHPUnit\Framework\TestCase;


class XMLTest extends TestCase
{
    protected function setUp()
    {
        $this->validXMLStr = file_get_contents(__DIR__ . '/fixtures/xml/person_caberger.xml');
        $this->xmlInstance = new \CEHD\App\XML\XML();
    }

    public function testValidatorInvalidXML()
    {
        $xmlStr = "Server 500 error";
        $this->assertFalse($this->xmlInstance->validateXML($xmlStr));
    }

    public function testValidatorValidXML()
    {
        $this->assertTrue($this->xmlInstance->validateXML($this->validXMLStr));
    }

    public function testValidatorEmptyString()
    {
        $this->assertFalse($this->xmlInstance->validateXML(" "));
    }
}
