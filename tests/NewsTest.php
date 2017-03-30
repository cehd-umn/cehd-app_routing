<?php

require_once __DIR__ . '/../cehd/app/XML/News.php';

use PHPUnit\Framework\TestCase;


class NewsTest extends TestCase
{
    protected function setUp()
    {
        $this->validRSSStr = file_get_contents(__DIR__ . '/fixtures/xml/newsfeed.xml');
        $this->newsInstance = new \CEHD\App\XML\News\News($this->validRSSStr);
    }

    public function testCreation()
    {
        $expected_story_count = 10;
        $expected_story_description = 'Panayiota Kendeou, associate professor in the Department of Educational Psychology&#8217;s Psychological Foundations of Education program, recently traveled to Sydney, Australia to present her work on the 12th biennial meeting of the Society for Applied Research in Memory and Cognition (SARMAC). Kendeou was part of a featured symposium&#8211;organized by two world-renowned experts on misinformation,  Ullrich Ecker (The &#8230; <a href="http://news.cehd.umn.edu/kendeou-presents-at-symposium-on-reducing-impact-of-misinformation-fake-news/" class="more-link">Continue reading <span class="screen-reader-text">Kendeou presents at symposium on reducing impact of misinformation, fake news</span> <span class="meta-nav">&#8594;</span></a>';
        $expected_story_link = "http://news.cehd.umn.edu/kendeou-presents-at-symposium-on-reducing-impact-of-misinformation-fake-news/";
        $expected_story_link_override = null;
        $expected_story_teaser = "Panayiota Kendeou, associate professor in the Department of Educational Psychology's Psychological Foundations of Education program, recently traveled to Sydney, Australia to present her work on the 12th biennial meeting of the Society for Applied Research in Memory and Cognition (SARMAC). Kendeou was part of a featured symposium--organized by two world-renowned experts on misinformation,  Ullrich Ecker (The University of Western Australia) and Stephan Lewandowsky (University of Bristol)--on research advances that reduce the impact of misinformation and fake news.";
        $expected_story_thumbnail = "http://news.cehd.umn.edu/wp-content/uploads/2016/09/kendeou_2.jpg";
        $expected_story_title = "Kendeou presents at symposium on reducing impact of misinformation, fake news";
        $expected_story_title_override = "Kendeou presents at symposium on reducing impact of misinformation, fake news";

        $this->assertTrue(is_array($this->newsInstance->stories));
        $this->assertEquals($expected_story_count, count($this->newsInstance->stories));
        $this->assertEquals($expected_story_description, $this->newsInstance->stories[0]->description);
        $this->assertEquals($expected_story_link, $this->newsInstance->stories[0]->link);
        $this->assertEquals($expected_story_link_override, $this->newsInstance->stories[0]->link_override);
        $this->assertEquals($expected_story_teaser, $this->newsInstance->stories[0]->teaser);
        $this->assertEquals($expected_story_thumbnail, $this->newsInstance->stories[0]->thumbnail);
        $this->assertEquals($expected_story_title_override, $this->newsInstance->stories[0]->title_override);
    }
}
