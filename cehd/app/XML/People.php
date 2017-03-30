<?php

namespace CEHD\App\People;
require_once __DIR__ . '/../../../cehd/app/XML/XML.php';

class BaseTag
{
    public $id;
    public $name;
    public $description;
}

class Tag extends BaseTag
{
    public $query_key = "tg";
}

class TagGroup extends BaseTag
{
    public $query_key = "en";
    public $abbreviation;
}

class Job
{
    public $deptId;

    public $deptIdDescription;

    public $status;

    public $description;

    public $umZDeptId;

    public $umZDeptIdDescription;
}

class Person extends \CEHD\App\XML\XML
{
    public $address_1;

    public $address_2;

    public $address_3;

    public $address_4;

    public $areasOfInterest;

    public $briefBio;

    public $city;

    public $curriculumVitaeAddr;

    public $dateDisplay;

    public $dateISO;

    public $degrees;

    public $expertise;

    public $fax;

    public $firstName;

    public $fullBio;

    public $headShot;

    public $internetId;

    public $jobs = [];

    public $lastName;

    public $middleName;

    public $name;

    public $nameFirstLast;

    public $nameSuffix;

    public $officeHours;

    public $phone;

    public $presentations;

    public $publications;

    public $state;

    public $tags = [];

    public $tagGroups = [];

    public $twitterName;

    public $webPage;

    public $workingTitle;

    public $zip;

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

    private function _setJobs($jobsData)
    {
        foreach($jobsData->job as $jobData)
        {
            $job = new Job();

            $job->deptId               = $this->toString($jobData->deptId);
            $job->deptIdDescription    = $this->toString($jobData->deptIdDescription);
            $job->status               = $this->toString($jobData->jobStatus);
            $job->description          = $this->toString($jobData->jobCodeDescr);
            $job->umZDeptId            = $this->toString($jobData->umZDeptId);
            $job->umZDeptIdDescription = $this->toString($jobData->umZDeptIdDescription);

            array_push($this->jobs, $job);
        }
    }

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

class People extends \CEHD\App\XML\XML
{
    public $people = [];

    function __construct($xml_string)
    {
        if ($this->validateXML($xml_string))
        {
            $xml = $this->_xmlStringToObject($xml_string);

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

    public function get_count() {
        return count($this->people);
    }
}
