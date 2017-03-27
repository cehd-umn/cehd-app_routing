<?php

namespace CEHD\App\People;
require_once __DIR__ . '/../../../cehd/app/XML/XML.php';

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
    // public $count;

    public $internetId;

    public $name;

    public $firstName;

    public $middleName;

    public $lastName;

    public $nameSuffix;

    public $nameFirstLast;

    public $phone;

    public $fax;

    public $address_1;

    public $address_2;

    public $address_3;

    public $address_4;

    public $city;

    public $state;

    public $zip;

    public $jobs;

    public $workingTitle;

    public $areasOfInterest;

    public $degrees;

    public $officeHours;

    public $headShot;

    public $webPage;

    public $curriculumVitaeAddr;

    public $twitterName;

    public $expertise;

    public $briefBio;

    public $fullBio;

    public $dateDisplay;

    public $dateISO;

    public $publications;

    public $presentations;

    function __construct($person)
    {
        $this->name =                $this->_get($person->name);

        $this->internetId =          $this->_get($person->internetId);

        $this->firstName =           $this->_get($person->firstName);

        $this->middleName =          $this->_get($person->middleName);

        $this->lastName =            $this->_get($person->lastName);

        $this->nameSuffix =          $this->_get($person->nameSuffix);

        $this->nameFirstLast =       $this->firstName . " " . $this->lastName;

        $this->phone =               $this->_get($person->phone);

        $this->fax =                 $this->_get($person->fax);

        $this->address_1 =           $this->_get($person->addr1);

        $this->address_2 =           $this->_get($person->addr2);

        $this->address_3 =           $this->_get($person->addr3);

        $this->address_4 =           $this->_get($person->addr4);

        $this->city =                $this->_get($person->city);

        $this->state =               $this->_get($person->state);

        $this->zip =                 $this->_get($person->zip);

        $this->jobs =                $this->_setJobs($person->jobs);

        $this->workingTitle =        $this->_get($person->workingTitle);

        $this->areasOfInterest =     $this->_get($person->areasOfInterest);

        $this->degrees =             $this->_get($person->degrees);

        $this->officeHours =         $this->_get($person->officeHours);

        $this->headshotAddress =     $this->_get($person->headshotAddr);

        $this->webPage =             $this->_get($person->webPage);

        $this->curriculumVitaeAddr = $this->_get($person->curriculumVitaeAddr);

        $this->twitterName =         $this->_get($person->twitterName);

        $this->expertise =           $this->_get($person->expertise);

        $this->briefBio =            $this->_get($person->briefBio);

        $this->fullBio =             $this->_get($person->fullBio);

        $this->dateDisplay =         $this->_get($person->dateDisplay);

        $this->dateISO =             $this->_get($person->dateISO);

        $this->publications =        $this->_setWorks(
                                        $person->publications,
                                        'publication'
                                     );

        $this->presentations =       $this->_setWorks(
                                        $person->presentations,
                                        'presentation'
                                     );
    }

    private function _setJobs($jobsData)
    {
        $jobs = [];

        foreach($jobsData as $jobData)
        {
            $job = new Job();

            $job->deptId               = trim((string) $jobData->deptId);
            $job->deptIdDescription    = trim((string) $jobData->deptIdDescription);
            $job->status               = trim((string) $jobData->jobStatus);
            $job->description          = trim((string) $jobData->jobCodeDescr);
            $job->umZDeptId            = trim((string) $jobData->umZDeptId);
            $job->umZDeptIdDescription = trim((string) $jobData->umZDeptIdDescription);

            array_push($jobs, $job);
        }

        return $jobs;
    }

    private function _get($obj)
    {
        $stringified = trim((string) $obj);
        return !empty($stringified) ? $stringified : null;
    }

    private function _isIterable($obj)
    {
        return is_array($obj) || $obj instanceof \Traversable;
    }

    private function _setWorks($worksData, $name)
    {
        $works = [];

        if ($this->_isIterable($worksData))
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
        $xml = $this->_xmlStringToObject($xml_string);

        foreach($xml->person as $person)
        {
            array_push($this->people, new Person($person));
        }
    }

    public function get_count() {
        return count($this->people);
    }
}
