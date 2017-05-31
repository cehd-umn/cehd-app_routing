<?php

require_once __DIR__ . '/../cehd/app/Card/Card.php';
require_once __DIR__ . '/../cehd/app/Card/ProgramCard.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use CEHD\App\Card\InvalidTypeException;

class ProgramCardTest extends TestCase
{
    private $cardInstance;

    protected function setUp()
    {
        $file_path = __DIR__ . "/fixtures/programs.yml";
        $this->cardsInstance = new \CEHD\App\Card\ProgramCard\ProgramCards();
        $this->cards = $this->cardsInstance->setCards($file_path)->getCards();
    }

    // @todo test all the exception types (this test fails)
    // public function testInvalidTypeException()
    // {
    //     $this->expectException(InvalidTypeException::class);
    //     $cardInstance = new \CEHD\App\Card\ProgramCard\ProgramCard();
    //     $card = $cardInstance->setCard("test", "string");
    // }

    public function testCreation()
    {
        $this->assertEquals(65, count($this->cards));
    }

    public function testCardsIsArray()
    {
        $this->assertTrue(is_array($this->cards));
    }

    public function testCardLink()
    {
        $card = $this->cardsInstance->getCard("Adult Education");
        $link = $card->getLink(0);
        $this->assertInstanceOf(\CEHD\App\Card\CardLink::class, $link);

        $expected_title = "Undergraduate Certificate";
        $expected_href = "http://www.cehd.umn.edu/olpd/undergrad-programs/AdEd/certificate.";
        $this->assertEquals($expected_title, $link->title);
        $this->assertEquals($expected_href, $link->href);
    }

    public function testCardHasExpectedAttributes()
    {
        $card = $this->cardsInstance->getCard("Adult Education");
        $expected_description = "Learn how adults gain new forms of knowledge, skills, attitudes, and values.";
        $this->assertEquals($expected_description, $card->description);
        $this->assertTrue($card->publish);
        $this->assertNull($card->meta);
        $this->assertCount(4, $card->categories_hyphenated);
        $this->assertEquals("teaching-learning-and-schools", $card->categories_hyphenated[0]);
    }

    public function testCardsGetCategories()
    {
        $categories = $this->cardsInstance->getCategories();
        $this->assertCount(7, $categories);
        $category = array_shift($categories);
        $this->assertEquals("Community and Leadership", $category);
    }
}
