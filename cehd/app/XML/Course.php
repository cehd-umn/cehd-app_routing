<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 * A class for consistently handling XML feeds from the People tool
 * Note: the actual XML feed doesn't have the proper structure to do this
 * correctly. Use the RSS feed (which is really just XML for the most part).
 *
 */

namespace CEHD\XML\Course;

require_once __DIR__ . '/../../../cehd/app/XML/XML.php';

/**
 * Course class
 *
 * Course objects are meant to be contained in a Courses object
 *
 * @extends XML
 */
class Course extends \CEHD\App\XML\XML
{
    /**
     * Class properties
     */

    /**
     * Grad or undergrad
     *
     * @type string
     */
    public $academicCareer;

    /**
     * Course catalog number, e.g., 5103
     *
     * @type string
     */
    public $catalogNumber;

    /**
     * @type string
     */
    public $courseDescription;

    /**
     * online or not online
     *
     * @type string
     */
    public $deliveryMethod;

    /**
     * Concise description
     *
     * @type string
     */
    public $description;

    /**
     * Internal program name
     *
     * @type string
     */
    public $program;

    /**
     * External program name
     *
     * @type string
     */
    public $programDescripton;

    /**
     * Subject acronym
     *
     * @type string
     */
    public $subject;

    /**
     * @type string
     */
    public $title;

    /**
     * Constructor
     *
     * Sets courses
     *
     * @param obj $course - XML object representing a single course
     */
    public function __construct($course)
    {
        $this->academicCareer = $this->toString($course->acad_career[0]);

        $this->catalogNumber = $this->toString($course->catalog_nbr[0]);

        $this->courseDescription = $this->toString($course->crse_description[0]);

        $this->deliveryMethod = $this->toString($course->deliveryMethod[0]);

        $this->description = $this->toString($course->description[0]);

        $this->program = $this->toString($course->Program[0]);

        $this->programDescription = $this->toString($course->ProgramDescr[0]);

        $this->subject = $this->toString($course->subject[0]);

        $this->title = $this->toString($course->title[0]);
    }
}

/**
 * Courses class
 *
 * Instantiate a Courses object to include Course objects
 *
 * @extends RSS
 */
class Courses extends \CEHD\App\XML\XML
{
    /**
     * @type array
     */
    private $_courses = [];

    /**
     * Constructor
     *
     * Sets courses
     *
     * @param str $xml_string - XML
     */
    public function __construct($xml_string)
    {
        $courses = $this->xmlStringToObject($xml_string);

        if ($courses) {
            $this->_setCourses($courses);
        }
        return $this;
    }

    /**
     * Sets $this->_courses
     *
     * @param object $xml - An XML object from an RSS feed
     */
    private function _setCourses($xml)
    {
        foreach ($xml->channel->item as $course) {
            array_push($this->_courses, new Course($course));
        }
    }

    /**
     * Interface to get courses
     *
     * @return array of Course instances
     */
    public function getCourses() {
        return $this->_courses;
    }
}
