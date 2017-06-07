<?php

require_once __DIR__ . '/../cehd/app/Card/Card.php';
require_once __DIR__ . '/../cehd/app/Card/PatternCard.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use CEHD\App\Card\InvalidTypeException;
use CEHD\App\Card\MissingElementException;
use CEHD\App\Card\InvalidKeyException;
use CEHD\App\Card\PatternCard\PatternCards;
use CEHD\App\Card\PatternCard\PatternCard;

class PatternCardTest extends TestCase
{
    private $_cardsInstance;

    private $_cards;

    private $_file_path = __DIR__ . "/fixtures/patterns.yml";

    protected function setUp()
    {
        $this->_cardsInstance = new PatternCards();
        $this->_cards = $this->_cardsInstance->setCards($this->_file_path)->getCards();
    }

    public function testBuildRoutesFromPatternData()
    {
        $patterns = $this->_cardsInstance->loadYaml($this->_file_path);
        $routes = $this->_cardsInstance->buildRoutesFromPatternData($patterns);
        $result = $routes["/patterns/v1/page/page-layout/"];
        $this->assertTrue($this->_cardsInstance->arrayContains("handler", $result));
        $this->assertNull($result->handler);
        $this->assertInstanceOf(PatternCard::class, $result);
    }

    public function testGetGroup()
    {
        $group = $this->_cardsInstance->getGroup("css");
        $this->assertTrue(is_array($group));
        $this->assertInstanceOf(PatternCard::class, $group[0]);
    }

    public function testGetGroupNames()
    {
        $group_names = $this->_cardsInstance->getGroupNames();
        $this->assertTrue(is_array($group_names));
        $this->assertEquals("page", $group_names[0]);
    }
}
