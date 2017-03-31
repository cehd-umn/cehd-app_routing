<?php

require_once __DIR__ . '/../cehd/app/XML/XML.php';

use PHPUnit\Framework\TestCase;


class XMLTest extends TestCase
{
    protected function setUp()
    {
        $this->validXMLStr = file_get_contents(__DIR__ . '/fixtures/xml/books.xml');
        $this->xmlInstance = new \CEHD\App\XML\XML();
        $this->books = $this->xmlInstance->xmlStringToObject($this->validXMLStr);
    }

    public function testInvalidXML()
    {
        // Test that invalid XML throws an exception
        $this->expectException(Exception::class);
        $xml = "<xml><open></xml>";
        $result = $this->xmlInstance->xmlStringToObject($xml);
    }

    public function testXmlStringtoObject()
    {
        $this->assertEquals(12, count($this->books->book));
        $this->assertEquals("Gambardella, Matthew", $this->books->book[0]->author);
        $this->assertEquals("XML Developer's Guide", $this->books->book[0]->title);
    }

    public function testToString()
    {
        $string_with_whitespace = " whitespace ";
        $int = 10;
        $this->assertEquals("whitespace", $this->xmlInstance->toString($string_with_whitespace));
        $this->assertTrue(is_string($this->xmlInstance->toString($int)));
    }

    public function testGetFromProperty()
    {
        $whitespace = "  ";
        $string_with_whitespace = " whitespace ";
        $this->assertEquals(null, $this->xmlInstance->getFromProperty($whitespace));
        $this->assertEquals("whitespace", $this->xmlInstance->getFromProperty($string_with_whitespace));
    }

    public function testToJSON()
    {
        $this->assertTrue(is_string($this->xmlInstance->toJSON()));
        $decoded = json_decode($this->xmlInstance->toJSON());

        // the JSON can be decoded, i.e., is valid
        $this->assertTrue(is_object($decoded));
    }
}
