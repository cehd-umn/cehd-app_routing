<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 * A class for handling Info Session feeds
 *
 */

namespace CEHD\XML\InfoSession;
require_once __DIR__ . '/../../../cehd/app/XML/Event.php';
require_once __DIR__ . '/../../../cehd/app/XML/XML.php';

/**
 * InfoSession class
 *
 * InfoSession objects are meant to be contained in an InfoSessions object
 *
 * @extends XML
 */
class InfoSession extends \CEHD\XML\Event\Event
{
    /**
     * Class properties
     */

     /**
      * @type string
      */
     public $code;

     /**
      * @type string
      */
     public $contact;

     /**
      * @type string
      */
     public $dateChange;

     /**
      * @type string
      */
     public $degree;

     /**
      * @type string
      */
     public $email;

     /**
      * @type string
      */
     public $eventId;

     /**
      * @type string
      */
     public $openSeats;

     /**
      * @type string
      */
     public $phone;

    /**
     * Class methods
     */

    /**
     * Constructor
     *
     * @param obj $session - XML object representing an info session
     */
    function __construct($session)
    {
        parent::__construct($session);

        $this->code = $this->getFromProperty($session->code);

        $this->contact = $this->getFromProperty($session->contact);

        $this->date = $this->getFromProperty($session->date);

        $this->day = $this->getFromProperty($session->dateDay);

        $this->month = $this->getFromProperty($this->getMonth());

        $this->fullMonth = $this->getFromProperty($this->getFullMonth());

        $this->shortMonth = $this->getFromProperty($this->getShortMonth());

        $this->timestamp = $this->getTimestamp();

        $this->year = $this->getYear();

        $this->dateChange = $this->getBoolFromProperty($session->dateChange);

        $this->degree = $this->getFromProperty($session->degree);

        $this->email = $this->getFromProperty($session->email);

        $this->eventId = $this->getFromProperty($session->eventId);

        $this->endTime = $this->getFromProperty($session->endTime);

        $this->id = $this->getFromProperty($session->infosessionId);

        $this->url = $this->getFromProperty($session->link);

        $this->location = $this->getFromProperty($session->location);

        $this->openSeats = $this->getFromProperty($session->openSeats);

        $this->phone = $this->getFromProperty($session->phone);

        $this->startTime = $this->getFromProperty($session->startTime);

        $this->title = $this->getFromProperty($session->title);

        $this->dateTimeEnd = $this->date . " " . $this->endTime;

        $this->dateTimeStart = $this->date . " " . $this->startTime;
    }
}

/**
 * InfoSessions class
 *
 * Typically a InfoSessions object is instantiated and InfoSession objects are
 * contained there.
 *
 * @example new \CEHD\App\XML\InfoSessions($validXmlStr);
 */
class InfoSessions extends \CEHD\App\XML\XML
{
    /**
     * Class properties
     */

    /**
     * @type array
     */
    public $infoSessions = [];

    /**
     * Class methods
     */

    /**
     * Constructor
     *
     * Sets infoSessions if the XML is valid
     *
     * @param str $xml_string - XML
     */
    function __construct($xml_string)
    {
        $xml = $this->xmlStringToObject($xml_string);

        if ($xml)
        {
            foreach($xml->channel->item as $infoSession)
            {
                array_push($this->infoSessions, new infoSession($infoSession));
            }
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

    /**
     * Gets infoSession count
     *
     * @return int
     */
    public function get_count() {
        return count($this->infoSessions);
    }
}
