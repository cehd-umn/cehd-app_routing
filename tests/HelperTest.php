<?php

require_once __DIR__ . '/../cehd/app/helpers/Helper.php';

use PHPUnit\Framework\TestCase;

class HelperTest extends TestCase
{
    protected function setUp()
    {
        $this->helper = new \CEHD\Helpers\Helper\Helper;
    }

    public function testHyphenate()
    {
        $hyphenated = $this->helper->hyphenate("This is a title with parentheses (parenthetical)");
        $expected_result = "this-is-a-title-with-parentheses-parenthetical";
        $this->assertEquals($expected_result, $hyphenated);
    }

    public function testStripDotHtml()
    {
        $stripped = $this->helper->stripDotHtml("HtmlFile.html");
        $expected_result = "HtmlFile";
        $this->assertEquals($expected_result, $stripped);

        $lowercase_result = $this->helper->stripDotHtml("HtmlFile.html", true);
        $expected_result = "htmlfile";
        $this->assertEquals($expected_result, $lowercase_result);

        $expected_result = "HtmlFilePath/";
        $no_dot_html_result = $this->helper->stripDotHtml($expected_result, true);
        $this->assertEquals($expected_result, $no_dot_html_result);
    }

    public function testPathToArray()
    {
        $path = "/edpsych-twig/people/";
        $path_array = $this->helper->pathToArray($path);
        $this->assertTrue(is_array($path_array));
        $this->assertEquals("2", count($path_array));
    }

    public function testGetLastFromPath()
    {
        $path = "/edpsych-twig/people/";
        $expected_result = "people";
        $actual_result = $this->helper->getLastFromPath($path);
        $this->assertEquals($expected_result, $actual_result);
    }
}
