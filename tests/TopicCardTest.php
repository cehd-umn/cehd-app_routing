<?php

require_once __DIR__ . '/../cehd/app/Card/Card.php';
require_once __DIR__ . '/../cehd/app/Card/TopicCard.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use CEHD\App\Card\InvalidTypeException;
use CEHD\App\Card\MissingElementException;
use CEHD\App\Card\InvalidKeyException;

class TopicCardTest extends TestCase
{
    private $cardInstance;

    protected function setUp()
    {
        $file_path = __DIR__ . "/fixtures/topics.yml";
        $this->cardsInstance = new \CEHD\App\Card\TopicCard\TopicCards();
        $this->cards = $this->cardsInstance->setCards($file_path)->getCards();
    }

    public function testGetInternalCategories()
    {
        $internal_categories = $this->cards["Access to Education"]->categories_internal;
        $expected_count = 1;
        $this->assertCount($expected_count, $internal_categories);
    }

    public function testGetCategoriesAndInternalCategories()
    {
        $categories = $this->cardsInstance->getCategoriesAndInternalCategories();
        $expected_research_count = 11;
        $expected_critical_count = 4;
        $this->assertCount($expected_research_count, $categories["Research areas"]);
        $this->assertcount($expected_critical_count, $categories["Critical issues"]);
    }
}
