<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 * @version 2.1.1
 *
 * The base Card class
 */

namespace CEHD\App\Card;

require_once __DIR__ . "/../../../vendor/autoload.php";
require_once __DIR__ . '/../../../cehd/app/Base.php';

use \CEHD\App\BaseClass;

class InvalidKeyException extends \Exception
{
}

class NotImplementedException extends \Exception
{
}

class MissingElementException extends \Exception
{
}

class InvalidTypeException extends \Exception
{
}

/**
 * Abstract Card class
 */
abstract class Card extends BaseClass
{
    /**
     * Class properties
     */
    protected $_categories = [];

    public $links = [];

    public $description;

    public $meta;

    public $publish;

    public $title;

    public $categories_hyphenated;

    protected function _validateCard($card)
    {
        $valid = true;

        if (!is_array($card)) {
            $msg = "card data must be in an associative array" . "\n\n" . var_export($card, true) . "\n";
            $valid = false;
            throw new InvalidTypeException($msg);
        } elseif ($this->arrayContains("publish", $card) && !is_bool($card["publish"])) {
            $msg = "card data must contain a publish status that is boolean" . "\n\n" . var_export($card, true) . "\n";
            $valid = false;
            throw new InvalidTypeException($msg);
        }

        $cards_should_have = [
            "categories",
            "description",
            "publish",
            "meta",
        ];

        foreach ($cards_should_have as $card_should_have) {
            if ($this->arrayDoesNotContain($card_should_have, $card)) {
                $msg = "card data must contain a " . $card_should_have . " element" . "\n\n" . var_export($card, true) . "\n";
                $valid = false;
                throw new MissingElementException($msg);
            }
        }

        return $valid;
    }

    private function _validateCategory($category, $key)
    {
        $valid = true;

        if ($this->categoryKeyExists($key)) {
            $valid = false;
            throw new InvalidKeyException("The key " . $key . " is already in use.");
        } elseif (gettype($category) !== "string") {
            $valid = false;
            throw new InvalidTypeException("'" . $category .
                "' should be a string. Got " . gettype($category) .
                " instead");
        }

        return $valid;
    }

    public function arrayDoesNotContain($needle, $haystack)
    {
        return !$this->arrayContains($needle, $haystack);
    }

    public function addCategory($category, $key=null)
    {
        try {
            if ($this->_validateCategory($category, $key)) {
                if (!$key) {
                    $key = $this->hyphenate($category);
                }

                $this->_categories[$key] = $category;
            }
        } catch (InvalidKeyException $e) {
            echo "Invalid key: " . $e->getMessage();
        } catch (InvalidTypeException $e) {
            echo "Invalid type: " . $e->getMessage();
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage();
        }
    }

    public function categoryKeyExists($key)
    {
        return $this->arrayContains($key, $this->_categories);
    }

    public function getCategory($key)
    {
        if (array_key_exists($key, $this->_categories)) {
            return $this->_categories[$key];
        }
    }

    public function getCategories()
    {
        return $this->_categories;
    }

    public function getCategoryKeys()
    {
        return array_keys($this->_categories);
    }

    public function addLink($link)
    {
        $l = new CardLink();
        $l->set($link);
        array_push($this->links, $l);
    }

    public function getLink($key)
    {
        if (array_key_exists($key, $this->links)) {
            return $this->links[$key];
        }
    }

    public function setCard($title, $card)
    {
        try {
            if ($this->_validateCard($card) === true) {
                if ($card["categories"]) {
                    foreach ($card["categories"] as $category) {
                        $this->addCategory($category);
                    }
                }

                if (array_key_exists("links", $card) && $card["links"]) {
                    foreach ($card["links"] as $link) {
                        $this->addLink($link);
                    }
                    asort($this->links);
                }

                $this->description = $card["description"];

                $this->publish = $card["publish"];

                $this->meta = $card["meta"];

                $this->title = $title;

                $this->categories_hyphenated = $this->getCategoryKeys();

                asort($this->_categories);

                return $this;
            }
        } catch (InvalidKeyException $e) {
            echo "Invalid key: " . $e->getMessage() . "\n";
        } catch (MissingElementException $e) {
            echo "Missing element: " . $e->getMessage() . "\n";
        } catch (InvalidTypeException $e) {
            echo "Invalid type: " . $e->getMessage() . "\n";
        } catch (Exception $e) {
            echo "Exception: " . $e->getMessage() . "\n";
        }
    }
}

abstract class CardGroup extends BaseClass
{
    protected $_cards = [];

    protected function _isValidCardInstance($card)
    {
        return $card instanceof Card;
    }

    public function setCard($title, $data)
    {
        throw new NotImplementedException("Classes that extend CardGroup must implement their own setCard method.");
    }

    public function setCards($yaml_file_path)
    {
        throw new NotImplementedException("Classes that extend CardGroup must implement their own setCards method.");
    }

    public function getCards()
    {
        return $this->_cards;
    }

    public function getCard($key)
    {
        if ($this->cardExists($key)) {
            return $this->_cards[$key];
        }
    }

    public function cardExists($key)
    {
        return $this->arrayContains($key, $this->_cards);
    }

    public function putCard($key, $card)
    {
        if ($this->cardExists($key) && $this->_isValidCardInstance($card)) {
            $this->_cards[$key] = $card;
        }
        return $this;
    }

    public function getCategories()
    {
        $results = [];
        foreach ($this->_cards as $card) {
            $categories = $card->getCategories();
            foreach ($categories as $key => $category) {
                $results[$key] = $category;
            }
        }

        return $results;
    }

    public function nullifyDescriptions()
    {
        foreach ($this->_cards as &$card) {
            $card->description = null;
        }
        return $this;
    }
}

class CardLink
{
    public $footnote_indicator;

    public $href;

    public $title;

    public function set($link_data)
    {
        $this->title = $link_data["title"];
        $this->href = $link_data["href"];

        if (array_key_exists("footnote_indicator", $link_data)) {
            $this->footnote_indicator = $link_data["footnote_indicator"];
        }
        return $this;
    }
}
