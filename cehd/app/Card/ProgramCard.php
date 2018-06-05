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

// use CEHD\App\Card\CardLink;
use CEHD\App\Card\Card;
use CEHD\App\Card\CardGroup;
use CEHD\App\Card\InvalidKeyException;
use CEHD\App\Card\MissingElementException;
use CEHD\App\Card\InvalidTypeException;

class ProgramCard extends Card
{
    use LinkSort;

    public $footnotes;

    public function setCard($title, $card)
    {
        try {
            if ($this->_validateCard($card) === true) {
                parent::setCard($title, $card);

                if ($this->arrayContains("footnotes", $card)) {
                    $this->footnotes = $card["footnotes"];
                }

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

    public function getDegrees()
    {
        $degrees = [];

        foreach ($this->links as $key => $link) {
            $degrees[$this->hyphenate($link->title)] = $link->title;
        }

        return $degrees;
    }
}

class ProgramCards extends CardGroup
{
    use LinkSort;

    private $_routes;

    public function setCard($title, $data)
    {
        $card = new ProgramCard();
        $card->setCard($title, $data);
        $card->sortCardLinks();
        return $card;
    }

    public function setCards($yaml_file_path)
    {
        $programs = $this->loadYaml($yaml_file_path);

        foreach ($programs as $title => $program) {
            $card = $this->setCard($title, $program);
            $this->_cards[$title] = $card;
        }

        return $this;
    }

    public function getCategoriesAndDegrees()
    {
        $category_groups = [
            "Areas of study" => [],
            "Degrees" => [],
        ];

        $category_groups["Areas of study"] = $this->getCategories();
        $degrees = [];

        foreach ($this->_cards as $card) {
            foreach ($card->links as $degree) {
                $key = $this->hyphenate($degree->title);
                $degrees[$key] = $degree->title;
                // @todo this works for now, but it muddies the purpose of this method, which is a getter and not a setter
                array_push($card->categories_hyphenated, $key);
            }
        }

        $this->sortLinks($degrees);
        $category_groups["Degrees"] = $degrees;

        return $category_groups;
    }
}

trait LinkSort
{
    private function _compareLinkObjectOrder($a, $b)
    {
        $order = [
            "Undergraduate Major",
            "Undergraduate Minor",
            "Undergraduate Certificate",
            "License",
            "Masters",
            "Ph.D.",
            "Graduate Minor",
            "Graduate Certificate",
        ];

        $pos_a = array_search($a->title, $order);
        $pos_b = array_search($b->title, $order);
        return $pos_a - $pos_b;
    }

    private function _compareLinkArrayOrder($a, $b)
    {
        $order = [
            "Undergraduate Major",
            "Undergraduate Minor",
            "Undergraduate Certificate",
            "License",
            "Masters",
            "Ph.D.",
            "Graduate Minor",
            "Graduate Certificate",
        ];

        $pos_a = array_search($a, $order);
        $pos_b = array_search($b, $order);
        return $pos_a - $pos_b;
    }

    // Operates on a Card object
    // Does not preserve keys
    public function sortCardLinks()
    {
        usort($this->links, [$this, '_compareLinkObjectOrder']);
    }

    // Operates on an arbitrary set of links
    // preserves keys
    public function sortLinks(&$links)
    {
        uasort($links, [$this, '_compareLinkArrayOrder']);
    }
}
