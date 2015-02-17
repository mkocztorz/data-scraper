<?php

namespace Mkocztorz\DataScraper\Tests\Extractor\Method;

use Mkocztorz\DataScraper\Extractor\Method\ExtractElementText;
use Mkocztorz\DataScraper\Std\SelectorProvider;
use Mkocztorz\DataScraper\Tests\PHPUnitTestCase;
use Polcode\MzkCrawlerBundle\Crawl\Extractor\Method\ExtractElementMethod;
use Symfony\Component\DomCrawler\Crawler;

class ExtractElementTextTest extends PHPUnitTestCase
{
    /**
     * @var SelectorProvider
     */
    protected $selectorProvider;

    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var string
     */
    protected $itemCssSelector;

    /**
     * @var string
     */
    protected $noItemsCssSelector;

    protected function getValidHtml()
    {
        return file_get_contents($this->getResourcesDir() . 'userlist.html');
    }

    public function setUp()
    {
        $this->selectorProvider = new SelectorProvider();
        $this->crawler = new Crawler();
        $this->crawler->addHtmlContent($this->getValidHtml());
        $this->itemCssSelector = '.list-group .list-group-item h4'; //will select first
        $this->noItemsCssSelector = '.not-existing-class';
    }

    public function test_extract_correct()
    {
        $method = new ExtractElementText($this->selectorProvider->get($this->itemCssSelector));
        $name = $method->extract($this->crawler);
        $this->assertEquals("John Doe", $name);
    }

    public function test_extract_empty()
    {
        $method = new ExtractElementText($this->selectorProvider->get($this->noItemsCssSelector));
        $value = $method->extract($this->crawler);

        $this->assertEquals("", $value);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_EXPECTED_ARRAY
     */
    public function test_invalid_params_not_array()
    {
        $params = null;
        new ExtractElementText($this->selectorProvider->get($this->itemCssSelector), $params);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_UNEXPECTED_VALUE
     */
    public function test_invalid_params_not_empty_array()
    {
        $params = [1,2,3];
        new ExtractElementText($this->selectorProvider->get($this->itemCssSelector), $params);
    }
}
 