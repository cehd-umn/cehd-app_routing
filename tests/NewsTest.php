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
        $expected_category_count = 6;
        $expected_category = "Educational Psychology";

        $this->assertTrue(is_array($this->newsInstance->stories));
        $this->assertEquals($expected_story_count, count($this->newsInstance->stories));
        $this->assertEquals($expected_story_description, $this->newsInstance->stories[0]->description);
        $this->assertEquals($expected_story_link, $this->newsInstance->stories[0]->link);
        $this->assertEquals($expected_story_link_override, $this->newsInstance->stories[0]->link_override);
        $this->assertEquals($expected_story_teaser, $this->newsInstance->stories[0]->teaser);
        $this->assertEquals($expected_story_thumbnail, $this->newsInstance->stories[0]->thumbnail);
        $this->assertEquals($expected_story_title_override, $this->newsInstance->stories[0]->title_override);
        $this->assertTrue(is_array($this->newsInstance->stories[0]->categories));
        $this->assertCount($expected_category_count, $this->newsInstance->stories[0]->categories);
        $this->assertEquals($expected_category, $this->newsInstance->stories[0]->categories[0]);

    }

    public function testSortByCategoryDecade()
    {
        $validRSSStr = file_get_contents(__DIR__ . '/fixtures/xml/newsfeed-alumni-notes.xml');
        $newsInstance = new \CEHD\App\XML\News\News($validRSSStr);
        $newsInstance->sortByCategoryDecade();
        $expected_story_count = 8;
        $expected_first_key = 1930;
        reset($newsInstance->stories);
        $first_key = key($newsInstance->stories);
        $first_story = array_shift($newsInstance->stories["1930"]);

        // Search for "193" to see if there's a category with a year in the 1930's
        $matches = [];
        foreach ($first_story->categories as $category)
        {
            if (preg_match("/193/", $category))
            {
                array_push($matches, $category);
            }
        }

        $this->assertEquals($expected_story_count, count($newsInstance->stories));
        $this->assertEquals($expected_first_key, $first_key);
        $this->assertFalse(empty($matches));
    }
}
