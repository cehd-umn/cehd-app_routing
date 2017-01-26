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
        $hyphenated = $this->helper::hyphenate("This is a title with parentheses (parenthetical)");
        $expected_result = "this-is-a-title-with-parentheses-parenthetical";
        $this->assertEquals($expected_result, $hyphenated);
    }
}
