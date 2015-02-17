<?php

namespace Mkocztorz\DataScraper\Tests\Std;

use Mkocztorz\DataScraper\Std\Extractor;
use Mkocztorz\DataScraper\Tests\PHPUnitTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ExtractorTest extends PHPUnitTestCase
{
    /**
     * @var Extractor
     */
    protected $extractor;

    /**
     * @var Crawler
     */
    protected $crawler;

    protected function getValidHtml()
    {
        return file_get_contents($this->getResourcesDir() . 'userlist.html');
    }

    public function setUp()
    {
        $this->crawler = new Crawler();
        $this->crawler->addHtmlContent($this->getValidHtml());
        $this->extractor = new Extractor();
    }

    public function test_test()
    {
        $this->assertTrue(true);
    }
}
 