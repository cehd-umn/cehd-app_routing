<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 * A class for handling Events feeds
 *
 */

namespace CEHD\XML\Event;

require_once __DIR__ . '/RSS.php';

class Audience

{
    /**
     * @type string
     */
    public $audienceCode;

    /**
     * @type string
     */
    public $audienceDescription;

}

class Occurrence

{
    /**
     * @type string
     */
    public $oDate;

    /**
     * @type string
     */
    public $oStartTime;

    /**
     * @type string
     */
    public $oEndTime;

}


/**
 * Event class
 *
 * Event objects are meant to be contained in an Events object
 *
 * @extends XML
 */
class Event extends \CEHD\App\XML\RSS\RSS
{
    /**
     * Class properties
     */

    /**
     * @type string
     */
    public $date;

    /**
     * @type string
     */
    public $dateTimeEnd;

    /**
     * @type string
     */
    public $dateTimeStart;

    /**
     * @type string
     */
    public $description;

    /**
     * @type string
     */
    public $endTime;

    /**
     * @type string
     */
    public $endHour;

    /**
     * @type string
     */
    public $endMinute;

    /**
     * @type string
     */
    public $id;

    /**
     * @type string
     */
    public $location;

    /**
     * @type string
     */
    public $locationURL;

    /**
     * @type object
     */
    public $audiences = [];

    /**
     * @type object
     */
    public $occurrences = [];

    /**
     * @type string
     */
    // public $audienceCode;

    /**
     * @type string
     */
    // public $audienceDescr;

    /**
     * @type string
     */
    public $shortDescription;

    /**
     * @type string
     */
    public $startTime;

    /**
     * @type string
     */
    public $startHour;

    /**
     * @type string
     */
    public $startMinute;

    /**
     * @type string
     */
    public $title;

    /**
     * @type string
     */
    public $url;

    /**
     * @type string
     */
    public $day;

    /**
     * @type string
     */
    public $dayOfWeek;

    /**
     * @type string
     */
    public $month;

    /**
     * @type string
     */
    public $fullMonth;

    /**
     * @type string
     */
    public $shortMonth;

    /**
     * @type string
     */
    public $year;

    /**
     * @type int
     */
    public $timestampStart;

    /**
     * @type int
     */
    public $timestampEnd;

    /**
     * @type string
     */
    public $relatedEventTitle;

    /**
     * @type string
     */
    public $relatedEventURL;

    /**
     * Class methods
     */

    /**
     * Constructor
     *
     * @param obj $person - XML object representing a person
     */
    public function __construct($event)
    {
        $this->date = $this->getFromProperty($event->eventDate);

        $this->day = $this->getFromProperty($event->dateDay);

        $this->dayOfWeek = $this->getFromProperty($event->dayOfWeek);

        $this->year = $this->getYear();

        $this->month = $this->getFromProperty($this->getMonth());

        $this->fullMonth = $this->getFromProperty($this->getFullMonth());

        $this->shortMonth = $this->getFromProperty($this->getShortMonth());

        $this->startTime = $this->getFromProperty($event->startTime);

        $this->startHour = $this->getFromProperty($this->getStartHour());

        $this->startMinute = $this->getFromProperty($this->getStartMinute());

        $this->endTime = $this->getFromProperty($event->endTime);

        $this->endHour = $this->getFromProperty($this->getEndHour());

        $this->endMinute = $this->getFromProperty($this->getEndMinute());

        $this->dateTimeEnd = $this->date . " " . $this->endTime;

        $this->dateTimeStart = $this->date . " " . $this->startTime;

        $this->timestampStart = $this->getTimestamp();

        $this->timestampEnd = $this->getTimestamp(true);

        $this->description = $this->getFromProperty($event->extendedDescr);

        $this->id = $this->getFromProperty($event->eventId);

        $this->location = $this->getFromProperty($event->location);

        $this->locationURL = $this->getFromProperty($event->locationURL);

        $this->shortDescription = $this->getFromProperty($event->briefDescr);

        $this->title = $this->getFromProperty($event->title);

        $this->url = $this->getFromProperty($event->link);

        $this->relatedEventTitle = $this->getFromProperty($event->relatedEvents);

        $this->relatedEventURL = $this->getFromProperty($event->relatedEventURL);

        $this->_setAudiences($event->audiences);

        $this->_setOccurrences($event->occurrences);
    }


    /**
     * Creates a Audience instance
     *
     * @param object $audienceData - An XML object from an representing an audience
     */
    private function _createAudience($audienceData)
    {
        $audience = new Audience();
        $audience->audienceCode   = $this->getFromProperty($audienceData->code);
        $audience->audienceDescription  = $this->getFromProperty($audienceData->description);
        return $audience;
    }

    /**
     * Sets $this->audiences
     *
     * @param object $audiencesData - An XML object from an representing audiences
     */
    private function _setAudiences($audiencesData)
    {
            foreach ($audiencesData->audience as $audienceData) {
                array_push($this->audiences, $this->_createAudience($audienceData));
            }
    }


    /**
     * Creates a Occurrence instance
     *
     * @param object $occurrenceData - An XML object from an representing an occurrence
     */
    private function _createOccurrence($oData)
    {
        $occurrence = new Occurrence();
        $occurrence->oDate   = $this->getFromProperty($oData->date);
        $occurrence->oStartTime   = $this->getFromProperty($oData->startTime);
        $occurrence->oEndTime   = $this->getFromProperty($oData->endTime);
        return $occurrence;
    }


    /**
     * Sets $this->occurrences
     *
     * @param object $occurrenceData - An XML object from an representing occurrences
     */
    private function _setOccurrences($osData)
    {
        foreach ($osData->occurrence as $occurrence) {
            array_push($this->occurrences, $this->_createOccurrence($osData));
        }
    }


    /**
     * Returns the numerical starting hour of the event start time
     *
     * @return string
     */
    public function getStartHour()
    {
        return $this->startTime ? date("h", strtotime($this->startTime)) : $this->startTime;
    }

    /**
     * Returns the numerical starting minute of the event
     *
     * @return string
     */
    public function getEndHour()
    {
        return $this->endTime ? date("h", strtotime($this->endTime)) : $this->endTime;
    }

    /**
     * Returns the numerical ending minute of the event
     *
     * @return string
     */
    public function getEndMinute()
    {
        return $this->endTime ? date("i", strtotime($this->endTime)) : $this->endTime;
    }

    /**
     * Returns the numerical starting minute of the event
     *
     * @return string
     */
    public function getStartMinute()
    {
        return $this->startTime ? date("i", strtotime($this->startTime)) : $this->startTime;
    }

    /**
     * Returns the numerical day of the event
     *
     * @return string
     */
    public function getDay()
    {
        return date("j", strtotime($this->date));
    }

    /**
     * Returns the numerical month of the event
     *
     * @return string
     */
    public function getMonth()
    {
        return date("n", strtotime($this->date));
    }

    /**
     * Returns the full spelling of the month of the event
     *
     * @return string
     */
    public function getFullMonth()
    {
        return date("F", strtotime($this->date));
    }

    /**
     * Returns the short spelling of the month of the event
     *
     * @return string
     */
    public function getShortMonth()
    {
        return date("M", strtotime($this->date));
    }

    /**
     * Returns the numerical year of the event
     *
     * @return string
     */
    public function getYear()
    {
        return date("Y", strtotime($this->date));
    }

    /**
     * Returns a Unix timestamp
     *
     * @param boolean $end - Whether or not to use the end date
     * @return string
     */
    public function getTimestamp($end=false)
    {
        if ($end) {
            return strtotime($this->dateTimeEnd);
        }
        return strtotime($this->dateTimeStart);
    }
}

/**
 * Events class
 *
 * Typically a Events object is instantiated and Event objects are contained
 * there.
 *
 * @example new \CEHD\App\XML\Events($validXmlStr);
 */
class Events extends \CEHD\App\XML\XML
{
    /**
     * Class properties
     */

    /**
     * @type array
     */
    public $events = [];

    /**
     * Class methods
     */

    /**
     * Constructor
     *
     * Sets events if the XML is valid
     *
     * @param str $xml_string - XML
     */
    public function __construct($xml_string)
    {
        $xml = $this->xmlStringToObject($xml_string);
        $count = (int) $xml->attributes()["totalCount"];

        if ($xml) {
            if ($count > 0) {
                foreach ($xml->event as $event) {
                    array_push($this->events, new Event($event));
                }
            }
        } else {
            foreach ($this->_errors as $msg) {
                throw new \Exception($msg);
            }

            $this->_clearErrors();
        }
    }

    /**
     * Gets event count
     *
     * @return int
     */
    public function get_count()
    {
        return count($this->events);
    }

    public function parse()
    {
        foreach ($this->events as $event) {
            // do stuff
        }
    }
}
