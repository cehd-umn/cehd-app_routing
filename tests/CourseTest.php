<?php

require_once __DIR__ . '/../cehd/app/XML/Course.php';

use PHPUnit\Framework\TestCase;


class CourseTest extends TestCase
{
    protected function setUp()
    {
        $this->validRSSStr = file_get_contents(__DIR__ . '/fixtures/xml/courses.xml');
        $this->courseInstance = new \CEHD\XML\Course\Courses($this->validRSSStr);
        $this->courses = $this->courseInstance->getCourses();
        $this->course = $this->courses[0];
    }

    public function testCreation()
    {
        $expected_count = 31;
        $courses = $this->courseInstance->getCourses();
        $this->assertEquals($expected_count, count($courses));
    }

    public function testCoursesIsArray()
    {
        $this->assertTrue(is_array($this->courses));
    }

    public function testCourseHasAcademicCareer()
    {
        $expected_academic_career = "GRAD";

        $this->assertEquals($expected_academic_career, $this->course->academicCareer);
    }

    public function testCourseHasExpectedCatalogNumber()
    {
        $expected_catalog_number = "5103";

        $this->assertEquals($expected_catalog_number, $this->course->catalogNumber);
    }

    public function testCourseHasExpectedCourseDescription()
    {
        $expected_course_description = "Examination of systems and philosophies of education globally with emphasis upon African, Asian, European, and North American nations. Foundations of comparative study with selected case studies.";

        $this->assertEquals($expected_course_description, $this->course->courseDescription);
    }

    public function testCourseHasExpectedDeliveryMethod()
    {
        $expected_delivery_method = "not online";

        $this->assertEquals($expected_delivery_method, $this->course->deliveryMethod);
    }

    public function testCourseHasExpectedDescription()
    {
        $expected_description = "Comparative Education";

        $this->assertEquals($expected_description, $this->course->description);
    }

    public function testCourseHasExpectedProgram()
    {
        $expected_program = "OLPD-CIDE";

        $this->assertEquals($expected_program, $this->course->program);
    }

    public function testCourseHasExpectedProgramDescription()
    {
        $expected_program_description = "CIDE Courses";

        $this->assertEquals($expected_program_description, $this->course->programDescription);
    }

    public function testCourseHasExpectedSubject()
    {
        $expected_subject = "EDPA";

        $this->assertEquals($expected_subject, $this->course->subject);
    }

    public function testCourseHasExpectedTitle()
    {
        $expected_title = "EDPA 5103";

        $this->assertEquals($expected_title, $this->course->title);
    }
}
