<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 *
 */
namespace CEHD\App\Card\TopicCard;

require_once __DIR__ . '/Card.php';

use CEHD\App\Card\Card;
use CEHD\App\Card\CardGroup;

class TopicCard extends Card
{
    public $title;

    public $tag_id;

    public $uri;

    public function setCard($title, $card)
    {
        parent::setCard($title, $card);

        $this->title = $title;

        $this->tagId = $card["tag_id"];

        $this->uri = "/topics/" . $this->hyphenate($title) . "/";
    }
}

class TopicCards extends CardGroup
{
    private $_routes;

    public $categories_internal;

    public function setCard($title, $data)
    {
        $card = new TopicCard();
        $card->setCard($title, $data);
        $card->categories_internal = $data["categories_internal"];
        return $card;
    }

    public function setCards($yaml_file_path)
    {
        $topics = $this->loadYaml($yaml_file_path);

        foreach($topics as $title => $topic)
        {
            $card = $this->setCard($title, $topic);
            $this->_cards[$title] = $card;
        }

        return $this;
    }

    public function getCardsWithHyphenatedKeys()
    {
        $new_cards = [];

        foreach ($this->_cards as $key => $card)
        {
            $new_cards[$this->hyphenate($key)] = $card;
        }

        return $new_cards;
    }

    public function getInternalCategories()
    {
        $results = [];
        foreach($this->_cards as $card)
        {
            if ($card->categories_internal)
            {
                foreach($card->categories_internal as $internal_category)
                {
                    $results[$this->hyphenate($internal_category)] = $internal_category;
                }
            }
        }

        return $results;
    }

    public function getCategoriesAndInternalCategories()
    {
        $category_groups = [
            "Research Areas" => [],
            "Critical Issues" => [],
        ];

        $category_groups["Research Areas"] = $this->getCategories();
        $category_groups["Critical Issues"] = $this->getInternalCategories();

        return $category_groups;
    }
}
