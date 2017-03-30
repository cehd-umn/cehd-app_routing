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
}
