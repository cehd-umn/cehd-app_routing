<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 * A class for consistently handling news RSS feeds in a testable way.
 *
 */

namespace CEHD\App\XML\News;
require_once __DIR__ . '/RSS.php';

/**
 * Story class
 *
 * Story objects are meant to be part of a News object
 *
 * @extends RSS
 */
class Story extends \CEHD\App\XML\RSS\RSS
{
    /**
     * Class properties
     */

    /**
     * Story description
     *
     * @type string
     */
    public $description;

    /**
     * URL for story
     *
     * @type string
     */
    public $link;

    /**
     * Optional URL for page that is not on news.cehd.umn.edu
     *
     * @type string
     */
    public $link_override;

    /**
     * Pithy description of the story
     *
     * @type string
     */
    public $teaser;

    /*
     * URL of the story thumbnail image
     * @type string
     */
    public $thumbnail;

    /*
     * The story title
     *
     * @type string
     */
    public $title;

    /*
     * An optional title that is different from the Wordpress post title
     *
     * @type string
     */
    public $title_override;

    /**
     * Constructor
     *
     * @param obj $story - XML object representing a single story
     */
    function __construct($story)
    {
        $this->description = $this->toString($story->description[0]);

        $this->link = $this->toString($story->link[0]);

        $this->link_override = $this->getContentAttr($story, "webpage");

        $this->teaser = $this->getContentAttr($story, "teaser");

        $this->thumbnail = $this->getContentAttr($story, "thumbnail");

        $this->title = $this->toString($story->title[0]);

        $this->title_override = $this->getContentAttr($story, "titleinternal" );
    }
}

/**
 * News class
 *
 * Typically a News object is instantiated and Story objects are contained
 * there.
 *
 * @example new \CEHD\App\XML\News\News($validRSSStr);
 */
class News extends \CEHD\App\XML\RSS\RSS
{
    /**
     * Class properties
     */

    /**
     * Stories
     *
     * @type array
     */
    public $stories = [];

    /**
     * Class methods
     */

    /**
     * Constructor
     *
     * Sets stories if the XML is valid; Throws exceptions otherwise
     *
     * @param str $xml_string - XML
     */
    function __construct($xml_string)
    {
        $stories = $this->xmlStringToObject($xml_string);

        if ($stories)
        {
            $this->_setStories($stories);
        }
    }

    /**
     * Sets $this->stories
     *
     * @param object $xml - An XML object from an RSS feed
     */
    private function _setStories($xml)
    {
        foreach ($xml->channel->item as $story)
        {
            array_push($this->stories, new Story($story));
        }
    }
}
