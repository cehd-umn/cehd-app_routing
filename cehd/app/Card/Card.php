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

/**
 * Abstract Card class
 */
abstract class Card extends BaseClass
{
    /**
     * Class properties
     */
    private $_categories = [];

    public $links = [];

    public $description;

    public $meta;

    public $publish;

    public $title;

    public $categories_hyphenated;

    public function addCategory($category, $key=null)
    {
        if (!$key)
        {
            $key = $this->hyphenate($category);
        }

        if (array_key_exists($key, $this->_categories))
        {
            throw new InvalidKeyException("The key " . $key . " is already in use.");
        }
        else
        {
            $this->_categories[$key] = $category;
        }
    }

    public function categoryKeyExists($key)
    {
        return array_key_exists($key, $this->categories);
    }

    public function getCategory($key)
    {
        if (array_key_exists($key, $this->_categories))
        {
            return $this->_categories[$key];
        }
        else
        {
            return null;
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
        $l->set($link["title"], $link["href"]);
        array_push($this->links, $l);
    }

    public function getLink($key)
    {
        if (array_key_exists($key, $this->links))
        {
            return $this->links[$key];
        }
    }

    public function setCard($title, $card)
    {
        if ($card["categories"])
        {
            foreach($card["categories"] as $category)
            {
                $this->addCategory($category);
            }
        }

        if ($card["links"])
        {
            foreach($card["links"] as $link)
            {
                $this->addLink($link);
            }
        }

        $this->description = $card["description"];

        $this->publish = $card["publish"];

        $this->meta = $card["meta"];

        $this->title = $title;

        $this->categories_hyphenated = $this->getCategoryKeys();
    }
}

abstract class CardGroup extends BaseClass
{
    protected $_cards = [];

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
        if (array_key_exists($key, $this->_cards))
        {
            return $this->_cards[$key];
        }
    }

    public function getCategories()
    {
        $results = [];
        foreach ($this->_cards as $card)
        {
            $categories = $card->getCategories();
            foreach ($categories as $key => $category)
            {
                $results[$key] = $category;
            }
        }

        asort($results);
        return $results;
    }
}

class CardLink
{
    public $title;

    public $href;

    public function set($title, $href)
    {
        $this->title = $title;
        $this->href = $href;
        return $this;
    }
}
