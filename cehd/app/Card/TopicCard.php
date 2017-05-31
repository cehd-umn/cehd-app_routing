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

use CEHD\App\Card\CardLink;
use CEHD\App\Card\Card;
use CEHD\App\Card\CardGroup;

class TopicCard extends Card
{
}

class TopicCards extends CardGroup
{
    private $_routes;

    public function setCard($title, $data)
    {
        $card = new TopicCard();
        $card->setCard($title, $data);
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
}
