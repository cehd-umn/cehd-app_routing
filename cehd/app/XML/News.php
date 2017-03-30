<?php

namespace CEHD\App\XML\News;
require_once __DIR__ . '/RSS.php';

class Story extends \CEHD\App\XML\RSS\RSS
{
    public $description;

    public $link;

    public $link_override;

    public $teaser;

    public $thumbnail;

    public $title;

    public $title_override;

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

class News extends \CEHD\App\XML\RSS\RSS
{
    public $stories = [];

    function __construct($xml_string)
    {
        if ($this->validateXML($xml_string))
        {
            $xml = $this->xmlStringToObject($xml_string);
            $this->_setStories($xml);
        }
        else
        {
            foreach($this->_errors as $msg)
            {
                throw new \Exception($msg);
            }

            $this->_clearErrors();
        }
    }

    private function _setStories($xml)
    {
        foreach ($xml->channel->item as $story)
        {
            array_push($this->stories, new Story($story));
        }
    }
}
