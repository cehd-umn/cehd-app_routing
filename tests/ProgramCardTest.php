<?php

require_once __DIR__ . '/../cehd/app/Card/Card.php';
require_once __DIR__ . '/../cehd/app/Card/ProgramCard.php';
require_once __DIR__ . '/../vendor/autoload.php';

use PHPUnit\Framework\TestCase;
use CEHD\App\Card\InvalidTypeException;
use CEHD\App\Card\MissingElementException;
use CEHD\App\Card\InvalidKeyException;

class ProgramCardTest extends TestCase
{
    private $cardInstance;

    protected function setUp()
    {
        $file_path = __DIR__ . "/fixtures/programs.yml";
        $this->cardsInstance = new \CEHD\App\Card\ProgramCard\ProgramCards();
        $this->cards = $this->cardsInstance->setCards($file_path)->getCards();
    }

    // @todo test all the exception types
    /**
     * @expectedException(InvalidTypeException)
     */
    public function testInvalidTypeException()
    {
        $cardInstance = new \CEHD\App\Card\ProgramCard\ProgramCard();
        $card = $cardInstance->setCard("test", "string");
    }

    /**
     * @expectedException(InvalidTypeException)
     */
    public function testPublishInvalidTypeException()
    {
        $cardInstance = new \CEHD\App\Card\ProgramCard\ProgramCard();
        $mock_card = [
            "publish" => "string",
            "footnotes" => null,
        ];
        $card = $cardInstance->setCard("test", $mock_card);
    }

    /**
     * @expectedException(MissingElementException)
     */
    public function testMissingElementException()
    {
        $cardInstance = new \CEHD\App\Card\ProgramCard\ProgramCard();
        $card = $cardInstance->setCard("test", ["publish" => true]);
    }

    public function testAddCategory()
    {
        $cardInstance = new \CEHD\App\Card\ProgramCard\ProgramCard();
        $data = [
            "publish" => true,
            "categories" => null,
            "description" => null,
            "meta" => null,
        ];
        $card = $cardInstance->setCard("test", $data);
        $card->addCategory("category", "key");
        $this->assertEquals($card->getCategory("key"), "category");
    }

    /**
     * @expectedException(InvalidKeyException)
     */
    public function testAddCategoryDuplicateKey()
    {
        $cardInstance = new \CEHD\App\Card\ProgramCard\ProgramCard();
        $data = [
            "publish" => true,
            "categories" => null,
            "description" => null,
            "meta" => null,
        ];
        $card = $cardInstance->setCard("test", $data);
        $card->addCategory("category", "key");
        $card->addCategory("category", "key");
    }

    public function testCategoryKeyExists()
    {
        $cardInstance = new \CEHD\App\Card\ProgramCard\ProgramCard();
        $data = [
            "publish" => true,
            "categories" => null,
            "description" => null,
            "meta" => null,
        ];
        $card = $cardInstance->setCard("test", $data);
        $card->addCategory("category", "key");
        $result = $card->categoryKeyExists("key");

        $this->assertEquals(true, $result);

        $result = $card->categoryKeyExists("nokey");

        $this->assertEquals(false, $result);
    }

    public function testGetCategory()
    {
        $cardInstance = new \CEHD\App\Card\ProgramCard\ProgramCard();
        $data = [
            "publish" => true,
            "categories" => null,
            "description" => null,
            "meta" => null,
        ];
        $card = $cardInstance->setCard("test", $data);
        $card->addCategory("category", "key");
        $result = $card->getCategory("key");

        $this->assertEquals("category", $result);

        $result = $card->getCategory("nokey");

        $this->assertEquals(null, $result);
    }

    public function testGetCategoryKeys()
    {
        $cardInstance = new \CEHD\App\Card\ProgramCard\ProgramCard();
        $data = [
            "publish" => true,
            "categories" => null,
            "description" => null,
            "meta" => null,
        ];
        $card = $cardInstance->setCard("test", $data);
        $card->addCategory("category", "key");
        $result = $card->getCategoryKeys();
        $expected = ["key"];

        $this->assertEquals($expected, $result);
    }

    public function testCreation()
    {
        $this->assertEquals(66, count($this->cards));
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
        $expected_href = "http://www.cehd.umn.edu/olpd/undergrad-programs/AdEd/certificate.html";
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

    public function testPutCard()
    {
        $key = "Adult Education";
        $card = $this->cardsInstance->getCard($key);
        $card->addCategory("foo bar");
        $this->cardsInstance->putCard($key, $card);
        $card = $this->cardsInstance->getCard($key);
        $this->assertTrue($card->categoryKeyExists("foo-bar"));
    }

    public function testNullifyDescriptions()
    {
        $key = "Adult Education";
        $card = $this->cardsInstance->getCard($key);
        $this->cardsInstance->nullifyDescriptions();
        $this->assertNull($card->description);
    }
}
