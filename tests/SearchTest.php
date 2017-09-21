<?php

require_once __DIR__ . '/../cehd/app/XML/Search.php';

use PHPUnit\Framework\TestCase;

class SearchTest extends TestCase
{
    private $search;

    protected function setUp()
    {
        $this->search = new \CEHD\App\Search\Search(
            [
                "primary" => [
                    "client" => "tc_education",
                    "name" => "College of Education + Human Development",
                    "site" => "tc_education_students",
                    "url" => "www.cehd.umn.edu",
                ],
                "secondary" => [
                    "client" => "searchumn",
                    "name" => "University of Minnesota",
                    "site" => "default_collection",
                    "url" => "www.umn.edu",
                ]
            ]
        );
    }

    /**
    * Call protected/private method of a class.
    *
    * @param object &$object    Instantiated object that we will run method on.
    * @param string $methodName Method name to call
    * @param array  $parameters Array of parameters to pass into method.
    * @link https://jtreminio.com/2013/03/unit-testing-tutorial-part-3-testing-protected-private-methods-coverage-reports-and-crap/
    *
    * @todo Create a BaseTest class for this method
    * @return mixed Method return.
    */
    public function invokeMethod(&$object, $methodName, array $parameters = array())
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }

    public function testValidateQueryParams()
    {
        $this->assertTrue(
            $this->invokeMethod(
                $this->search,
                '_validateQueryParams',
                [
                    ["q" => "query"],
                ]
            )
        );

        $this->assertFalse(
            $this->invokeMethod(
                $this->search,
                '_validateQueryParams',
                [
                    [],
                ]
            )
        );
    }

    public function testGetStart()
    {
        $start = $this->invokeMethod(
            $this->search,
            '_getStart',
            [
                [],
            ]
        );

        $this->assertEquals(0, $start);

        $start = $this->invokeMethod(
            $this->search,
            '_getStart',
            [
                ['start' => 10],
            ]
        );

        $this->assertEquals(10, $start);
    }

    public function testGet()
    {
        $query_params = [
            'q' => "social work"
        ];

        $results = $this->search->getResults($query_params);
        $this->assertTrue(is_array($results));
        $this->assertEquals(
            $results['primary']['name'],
            "College of Education + Human Development");
        $this->assertEquals(count($results['primary']['results']), 10);
    }

    public function testGetWithDifferentParams()
    {
        $query_params = [
            'q' => "social work",
            'num' => 3,
        ];

        $results = $this->search->getResults($query_params);
        $this->assertEquals(count($results['primary']['results']), 3);
    }
}
