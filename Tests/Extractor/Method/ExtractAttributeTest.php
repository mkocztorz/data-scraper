<?php

namespace Mkocztorz\DataScraper\Tests\Extractor\Method;

use Mkocztorz\DataScraper\Extractor\Method\ExtractAttribute;
use Mkocztorz\DataScraper\Std\SelectorProvider;
use Mkocztorz\DataScraper\Tests\PHPUnitTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ExtractAttributeTest extends PHPUnitTestCase
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
        $this->itemCssSelector = '.list-group .list-group-item'; //will select first
        $this->noItemsCssSelector = '.not-existing-class';
    }


    public function test_extract_correct()
    {
        $method = new ExtractAttribute($this->selectorProvider->get($this->itemCssSelector), ['attr'=>'id']);
        $id = $method->extract($this->crawler);
        $this->assertEquals("user-22", $id);
    }

    public function test_extract_empty()
    {
        $method = new ExtractAttribute($this->selectorProvider->get($this->noItemsCssSelector), ['attr'=>'id']);
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
        new ExtractAttribute($this->selectorProvider->get($this->itemCssSelector), $params);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_UNEXPECTED_VALUE
     */
    public function test_invalid_params_not_empty_array()
    {
        $params = ['attr'=>1,2,3];
        new ExtractAttribute($this->selectorProvider->get($this->itemCssSelector), $params);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_EXPECTED_VALUE_MISSING
     */
    public function test_invalid_param()
    {
        $params = ['not-attr'=>'test'];
        new ExtractAttribute($this->selectorProvider->get($this->itemCssSelector), $params);
    }
}
 