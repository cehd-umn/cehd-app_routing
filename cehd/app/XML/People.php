<?php
/**
 * @author Tony Thomas <thoma127@umn.edu>
 * @license GPL 3.0
 * @package CEHD App
 *
 * A class for consistently handling XML feeds from the People tool
 *
 */

namespace CEHD\App\People;
require_once __DIR__ . '/../../../cehd/app/XML/XML.php';

/**
 * BaseTag class
 *
 * A parent class that contains properties common to tags and tagGroups
 */
class BaseTag
{
    /**
     * Class properties
     */

    /**
     * tag/tagGroup id
     *
     * @type string
     */
    public $id;

    /**
     * tag/tagGroup name
     *
     * @type string
     */
    public $name;

    /**
     * tag/tagGroup description
     *
     * @type string
     */
    public $description;
}

/**
 * Tag class
 *
 * A class for properties unique to tags
 *
 * Tags are part of a Person object
 *
 * @extends BaseTag
 */
class Tag extends BaseTag
{
    /**
     * Class properties
     */

    /**
     * the name of the People tool query parameter; i.e., tg=38
     *
     * @type string
     */
    public $query_key = "tg";
}

/**
 * TagGroup class
 *
 * A class for properties unique to tagGroups
 *
 * TagGroups are part of a Person object
 *
 * @extends BaseTag
 */
class TagGroup extends BaseTag
{
    /**
     * Class properties
     */

    /**
     * the name of the People tool query parameter; i.e., en=38
     *
     * @type string
     */
    public $query_key = "en";

    /**
     * An abbreviation representing the tagGroup
     *
     * @type string
     */
    public $abbreviation;
}

/**
 * Job class
 *
 * A class representing faculty jobs
 *
 * Jobs are part of a Person object
 *
 */
class Job
{
    /**
     * Class properties
     */

    /**
     * the internal department id
     *
     * @type string
     */
    public $deptId;

    /**
     * the internal department description
     *
     * @type string
     */
    public $deptIdDescription;

    /**
     * Job status
     *
     * @type string
     */
    public $status;

    /**
     * Job description
     *
     * @type string
     */
    public $description;

    /**
     * UMN Dept id
     *
     * @type string
     */
    public $umZDeptId;

    /**
     * UMN Dept description
     *
     * @type string
     */
    public $umZDeptIdDescription;
}

/**
 * Person class
 *
 * Person objects are meant to be contained in a People object
 *
 * @extends XML
 */
class Person extends \CEHD\App\XML\XML
{
    /**
     * Class properties
     */

    /**
     * @type string
     */
    public $address_1;

    /**
     * @type string
     */
    public $address_2;

    /**
     * @type string
     */
    public $address_3;

    /**
     * @type string
     */
    public $address_4;

    /**
     * @type string
     */
    public $areasOfInterest;

    /**
     * @type string
     */
    public $briefBio;

    /**
     * @type string
     */
    public $city;

    /**
     * @type string
     */
    public $curriculumVitaeAddr;

    /**
     * @type string
     */
    public $dateDisplay;

    /**
     * @type string
     */
    public $dateISO;

    /**
     * @type string
     */
    public $degrees;

    /**
     * @type string
     */
    public $expertise;

    /**
     * @type string
     */
    public $fax;

    /**
     * @type string
     */
    public $firstName;

    /**
     * @type string
     */
    public $fullBio;

    /**
     * @type string
     */
    public $headShot;

    /**
     * @type string
     */
    public $internetId;

    /**
     * @type array
     */
    public $jobs = [];

    /**
     * @type string
     */
    public $lastName;

    /**
     * @type string
     */
    public $middleName;

    /**
     * @type string
     */
    public $name;

    /**
     * @type string
     */
    public $nameFirstLast;

    /**
     * @type string
     */
    public $nameSuffix;

    /**
     * @type string
     */
    public $officeHours;

    /**
     * @type string
     */
    public $phone;

    /**
     * @type string
     */
    public $presentations;

    /**
     * @type string
     */
    public $publications;

    /**
     * @type string
     */
    public $state;

    /**
     * @type array
     */
    public $tags = [];

    /**
     * @type array
     */
    public $tagGroups = [];

    /**
     * @type string
     */
    public $twitterName;

    /**
     * @type string
     */
    public $webPage;

    /**
     * @type string
     */
    public $workingTitle;

    /**
     * @type string
     */
    public $zip;

    /**
     * Class methods
     */

    /**
     * Constructor
     *
     * @param obj $person - XML object representing a person
     */
    function __construct($person)
    {
        $this->name =                $this->getFromProperty($person->name);

        $this->internetId =          $this->getFromProperty($person->internetId);

        $this->firstName =           $this->getFromProperty($person->firstName);

        $this->middleName =          $this->getFromProperty($person->middleName);

        $this->lastName =            $this->getFromProperty($person->lastName);

        $this->nameSuffix =          $this->getFromProperty($person->nameSuffix);

        $this->nameFirstLast =       $this->firstName . " " . $this->lastName;

        $this->phone =               $this->getFromProperty($person->phone);

        $this->fax =                 $this->getFromProperty($person->fax);

        $this->address_1 =           $this->getFromProperty($person->addr1);

        $this->address_2 =           $this->getFromProperty($person->addr2);

        $this->address_3 =           $this->getFromProperty($person->addr3);

        $this->address_4 =           $this->getFromProperty($person->addr4);

        $this->city =                $this->getFromProperty($person->city);

        $this->state =               $this->getFromProperty($person->state);

        $this->zip =                 $this->getFromProperty($person->zip);

        $this->workingTitle =        $this->getFromProperty($person->workingTitle);

        $this->areasOfInterest =     $this->getFromProperty($person->areasOfInterest);

        $this->degrees =             $this->getFromProperty($person->degrees);

        $this->officeHours =         $this->getFromProperty($person->officeHours);

        $this->headshotAddress =     $this->getFromProperty($person->headshotAddr);

        $this->webPage =             $this->getFromProperty($person->webPage);

        $this->curriculumVitaeAddr = $this->getFromProperty($person->curriculumVitaeAddr);

        $this->tags =                $this->_setTagData($person->tags, "tag");

        $this->tagGroups =           $this->_setTagData($person->tagGroups, "tagGroup");

        $this->twitterName =         $this->getFromProperty($person->twitterName);

        $this->expertise =           $this->getFromProperty($person->expertise);

        $this->briefBio =            $this->getFromProperty($person->briefBio);

        $this->fullBio =             $this->getFromProperty($person->fullBio);

        $this->dateDisplay =         $this->getFromProperty($person->dateDisplay);

        $this->dateISO =             $this->getFromProperty($person->dateISO);

        $this->publications =        $this->_setWorks(
                                        $person->publications,
                                        'publication'
                                     );

        $this->presentations =       $this->_setWorks(
                                        $person->presentations,
                                        'presentation'
                                     );

        $this->_setJobs($person->jobs);
    }

    /**
     * Sets $this->tags or $this->tagGroups
     *
     * The logic for setting either is the same. Properties vary between the
     * two.
     *
     * @param object $tagData - An XML object from an representing a tag or TagGroup
     * @param string $nodeName - Either "tag" or "tagGroup"
     */
    private function _setTagData($tagData, $nodeName)
    {
        $objects = [];

        foreach($tagData->$nodeName as $tagData)
        {
            $qualified_class_name = __NAMESPACE__ . "\\" . ucfirst($nodeName);
            $class = new $qualified_class_name;

            foreach($tagData->attributes() as $property_name => $property_value)
            {
                if (strpos($property_name, "Id"))
                {
                    $property_name = "id";
                }

                if (strpos($property_name, "Descr"))
                {
                    $property_name = "description";
                }

                if (strpos($property_name, "Abbr"))
                {
                    $property_name = "abbreviation";
                }

                if (property_exists($class, $property_name))
                {
                    $class->$property_name = $this->toString($property_value);
                }
            }
            array_push($objects, $class);
        }
        return $objects;
    }

    /**
     * Creates a Job instance
     *
     * @param object $jobData - An XML object from an representing a job
     */
    private function _createJob($jobData)
    {
        $job = new Job();

        $job->deptId               = $this->toString($jobData->deptId);
        $job->deptIdDescription    = $this->toString($jobData->deptIdDescr);
        $job->status               = $this->toString($jobData->jobStatus);
        $job->description          = $this->toString($jobData->jobCodeDescr);
        $job->umZDeptId            = $this->toString($jobData->umZDeptId);
        $job->umZDeptIdDescription = $this->toString($jobData->umZDeptIdDescr);
        return $job;
    }

    /**
     * Sets $this->jobs
     *
     * @param object $jobsData - An XML object from an representing jobs
     */
    private function _setJobs($jobsData)
    {
        if (property_exists($jobsData, "job"))
        {

            foreach($jobsData->job as $jobData)
            {
                array_push($this->jobs, $this->_createJob($jobData));
            }
        }
    }

    /**
     * Sets $this->presentations or $this->publications
     *
     * @param object $worksData - An XML object from an representing jobs
     * @param string $name - Either "presentation" or "publication"
     */
    private function _setWorks($worksData, $name)
    {
        $works = [];

        if ($this->isIterable($worksData))
        {
            foreach($worksData->{$name} as $workData)
            {
                $stringified = trim((string) $workData);
                if (!empty($stringified))
                {
                    array_push($works, $stringified);
                }
            }
        }
        else
        {
            $stringified = trim((string) $worksData->{$name});
            array_push($works, $stringified);
        }

        return $works;
    }
}

/**
 * People class
 *
 * Typically a People object is instantiated and Person objects are contained
 * there.
 *
 * @example new \CEHD\App\People\People($validXmlStr);
 */
class People extends \CEHD\App\XML\XML
{
    /**
     * Class properties
     */

    /**
     * @type array
     */
    public $people = [];

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
        $xml = $this->xmlStringToObject($xml_string);

        if ($xml)
        {
            foreach($xml->person as $person)
            {
                array_push($this->people, new Person($person));
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
     * Gets people count
     *
     * @return int
     */
    public function get_count() {
        return count($this->people);
    }
}
