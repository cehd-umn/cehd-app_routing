<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 *
 */
namespace CEHD\App\Card\ProgramCard;

require_once __DIR__ . '/Card.php';

use CEHD\App\Card\CardLink;
use CEHD\App\Card\Card;
use CEHD\App\Card\CardGroup;

class ProgramCard extends Card
{
}

class ProgramCards extends CardGroup
{
    private $_routes;

    public function setCard($title, $data)
    {
        $card = new ProgramCard();
        $card->setCard($title, $data);
        return $card;
    }

    public function setCards($yaml_file_path)
    {
        $programs = $this->loadYaml($yaml_file_path);

        foreach($programs as $title => $program)
        {
            $card = $this->setCard($title, $program);
            $this->_cards[$title] = $card;
        }

        return $this;
    }
}
