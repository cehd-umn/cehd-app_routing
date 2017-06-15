<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 *
 */
namespace CEHD\App\Card\PatternCard;

require_once __DIR__ . '/Card.php';
require_once __DIR__ . '/../../../vendor/autoload.php';

use CEHD\App\Card\CardLink;
use CEHD\App\Card\Card;
use CEHD\App\Card\CardGroup;
use UMN\CEHD\Dict;

class PatternCard extends Card
{
    public $group;

    public $handler;

    public $type;

    public $template;

    public $twig_files;

    public function setCard($title, $card)
    {
        parent::setCard($title, $card);

        $this->group = $card["group"];

        $this->handler = $card["handler"];

        $this->type = $card["type"];

        $this->template = $card["template"];

        $this->twig_files = $card["twig_files"];
    }
}

class PatternCards extends CardGroup
{
    public function getGroup($group)
    {
        $patterns = [];

        foreach ($this->_cards as $pattern)
        {
            if ($pattern->group === $group)
            {
                array_push($patterns, $pattern);
            }
        }
        return $patterns;
    }

    public function getGroupNames()
    {
        $groups = [];

        foreach($this->_cards as $pattern)
        {
            array_push($groups, $pattern->group);
        }
        sort($groups);
        return array_unique($groups);
    }

    public function buildRouteFromPattern($group, $title, $group_route=false)
    {
        if ($group_route)
        {
            return "/patterns/v1/" . $group . "/";
        }
        else
        {
            return "/patterns/v1/" . $group . "/" . $this->hyphenate($title) . "/";
        }
    }

    public function buildRoutesFromPatternData()
    {
        $routes = [];

        foreach ($this->_cards as $title => $pattern)
        {
            $route = $this->buildRouteFromPattern($pattern->group, $title);
            $routes[$route] = $pattern;
        }

        $groups = $this->getGroupNames();

        foreach($groups as $group)
        {
            $route = $this->buildRouteFromPattern($group, $group, true);
            $routes[$route] = new Dict\Dict(["handler" => "patternGroupHandler"]);
        }

        return $routes;
    }

    public function setCard($title, $data)
    {
        $card = new PatternCard();
        $card->setCard($title, $data);
        return $card;
    }

    public function setCards($yaml_file_path)
    {
        $patterns = $this->loadYaml($yaml_file_path);

        foreach($patterns as $title => $pattern)
        {
            $card = $this->setCard($title, $pattern);
            $this->_cards[$title] = $card;
        }

        return $this;
    }
}
