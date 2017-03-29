<?php

require_once __DIR__ . '/../cehd/app/App/App.php';
require_once __DIR__ . '/../cehd/app/People/People.php';
require_once __DIR__ . '/../cehd/app/Response/BaseResponse.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;

class AppTest extends TestCase
{
    private $appInstance;

    protected function setUp()
    {
        $appConfigFilePath = __DIR__ . '/fixtures/config/app-config.yml';
        $navFilePath =       __DIR__ . '/fixtures/nav.yml';
        $routesFilePath =    __DIR__ . '/fixtures/routes.yml';
        $this->appInstance = new \CEHD\App\App\BaseApp([
            "configs" => [
                $appConfigFilePath,
            ],
            "nav" => $navFilePath,
            "routes" => $routesFilePath,
        ]);
    }

    public function testCreation()
    {
        // the router is an instance of Klein
        $this->assertInstanceOf(Klein\Klein::class, $this->appInstance->router);

        // template is an instance of Twig
        $this->assertInstanceOf(\Twig_Environment::class, $this->appInstance->template);

        // response is an instance of \CEHD\App\BaseResponse
        $this->assertInstanceOf(\CEHD\App\Response\BaseResponse::class, $this->appInstance->response);

        // the config is properly set
        $this->assertTrue($this->appInstance->config['debug']);
        $this->assertEquals("/assets", $this->appInstance->config['asset_base']);

        // nav is set
        $this->assertTrue(is_array($this->appInstance->nav_items));
        $this->assertEquals(8 ,count($this->appInstance->nav_items));

        $names = [
            "Home",
            "Programs & Degrees",
            "Current Students",
            "People",
            "Research",
            "Giving",
            "Alumni",
            "About",
        ];

        // the nav has the expected structure
        foreach(range(0, 7) as $i)
        {
            $this->assertEquals($names[$i] ,$this->appInstance->nav_items[$i]["name"]);
        }
    }

    public function testAlternateConfig()
    {
        $appConfigFilePath = __DIR__ . '/fixtures/config/app-config.yml';
        $yaConfigFilePath  = __DIR__ . '/fixtures/config/ya-config.yml';
        $navFilePath       = __DIR__ . '/fixtures/nav.yml';
        $routesFilePath    = __DIR__ . '/fixtures/routes.yml';
        $this->appInstance = new \CEHD\App\App\BaseApp([
            "configs" => [
                $appConfigFilePath,
                $yaConfigFilePath,
            ],
            "nav" => $navFilePath,
            "routes" => $routesFilePath,
        ]);

        $this->assertEquals("/new/base/assets", $this->appInstance->config['asset_base']);
    }

    public function testHyphenate()
    {
        $hyphenated = $this->appInstance->hyphenate("This is a title with parentheses (parenthetical)");
        $expected_result = "this-is-a-title-with-parentheses-parenthetical";
        $this->assertEquals($expected_result, $hyphenated);
    }

    public function testStripDotHtml()
    {
        $stripped = $this->appInstance->stripDotHtml("HtmlFile.html");
        $expected_result = "HtmlFile";
        $this->assertEquals($expected_result, $stripped);

        $lowercase_result = $this->appInstance->stripDotHtml("HtmlFile.html", true);
        $expected_result = "htmlfile";
        $this->assertEquals($expected_result, $lowercase_result);

        $expected_result = "HtmlFilePath/";
        $no_dot_html_result = $this->appInstance->stripDotHtml($expected_result, true);
        $this->assertEquals($expected_result, $no_dot_html_result);
    }

    public function testPathToArray()
    {
        $path = "/edpsych-twig/people/";
        $path_array = $this->appInstance->pathToArray($path);
        $this->assertTrue(is_array($path_array));
        $this->assertEquals("2", count($path_array));
    }

    public function testGetLastFromPath()
    {
        $path = "/edpsych-twig/people/";
        $expected_result = "people";
        $actual_result = $this->appInstance->getLastFromPath($path);
        $this->assertEquals($expected_result, $actual_result);
    }

    public function testIsIterable()
    {
        $xmlStr = file_get_contents(__DIR__ . '/fixtures/xml/person_caberger.xml');
        $classInstance = new \CEHD\App\People\People($xmlStr);

        // strings will iterate, but not in a way we expect so we want false
        $this->assertFalse($this->appInstance->isIterable("string"));

        // booleans are not iterable
        $this->assertFalse($this->appInstance->isIterable(false));

        // integers and floats are not iterable
        $this->assertFalse($this->appInstance->isIterable(0));
        $this->assertFalse($this->appInstance->isIterable(1.1));

        // neither is null
        $this->assertFalse($this->appInstance->isIterable(null));

        // arrays and classes will work in a foreach loop
        $this->assertTrue($this->appInstance->isIterable([1, 2, 3]));
        $this->assertTrue($this->appInstance->isIterable($classInstance));
    }

    public function testParseXMLErrors()
    {
        $errors = null;
        libxml_use_internal_errors(true);

        $invalidXMLStr = "<xml><open></xml>";

        // produce an XML parsing error
        $doc = simplexml_load_string($invalidXMLStr, null, LIBXML_NOCDATA);
        $errors = libxml_get_errors();
        libxml_clear_errors();

        $results = $this->appInstance->parseXMLErrors($errors);
        $this->assertTrue(is_array($results));
        $this->assertFalse(empty($errors));
        $this->assertEquals(2, count($results));

        $this->assertTrue(strpos($results[0], "Opening and ending tag mismatch") > 0);
    }
}
