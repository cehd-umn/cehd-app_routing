<?php

require_once __DIR__ . '/../cehd/app/Response/BaseResponse.php';

use PHPUnit\Framework\TestCase;


class BaseResponseTest extends TestCase
{
    protected $response;

    protected $xmlStr;

    protected function setUp()
    {
        $this->response = new \CEHD\App\Response\BaseResponse();

        $this->xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/results.xml');
    }

    public function testXmlStrToObject()
    {
        $obj = $this->response->xmlStrToObject($this->xmlStr);

        // We get an object back
        $this->assertTrue(is_object($obj));

        // Results
        $this->assertTrue(is_object($obj->RES->R));

        // Result Title
        $this->assertTrue(is_object($obj->RES->R->T));


        // There are 10 results
        $this->assertEquals(count($obj->RES->R), 10);
    }

    public function testgetSearchResultsFromXMLObject()
    {
        $obj = $this->response->xmlStrToObject($this->xmlStr);
        $converted = $this->response->getSearchResultsFromXMLObject($obj);
        $this->assertEquals(count($converted), 10);
        $this->assertTrue(is_array($converted));
        $this->assertArrayHasKey('title', $converted[0]);
        $this->assertArrayHasKey('description', $converted[0]);
        $this->assertArrayHasKey('link', $converted[0]);
    }
}
