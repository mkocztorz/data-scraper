<?php
namespace Mkocztorz\DataScraper\Tests\Extractor\Method;


use Mkocztorz\DataScraper\Extractor\Method\ExtractElementTextPattern;
use Mkocztorz\DataScraper\Std\SelectorProvider;
use Mkocztorz\DataScraper\Tests\PHPUnitTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ExtractElementTextPatternTest extends PHPUnitTestCase
{
    /**
     * @var string
     */
    protected $validCorrectPattern;
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

    /**
     * @var string
     */
    protected $validNoMatchPattern;

    /**
     * @var string
     */
    protected $invalidPattern;

    /**
     * @var string
     */
    protected $validPatternWrongParam;

    protected function getValidHtml()
    {
        return file_get_contents($this->getResourcesDir() . 'userlist.html');
    }

    public function setUp()
    {
        $this->validCorrectPattern = '/UID-(?P<value>\d+)/';
        $this->validNoMatchPattern = '/NO-MATCH_STRING-(?P<value>\d+)/';
        $this->validPatternWrongParam = '/NO-MATCH_STRING-(?P<wrong>\d+)/';
        $this->invalidPattern = '/$%#$>\d+)))/';
        $this->selectorProvider = new SelectorProvider();
        $this->crawler = new Crawler();
        $this->crawler->addHtmlContent($this->getValidHtml());
        $this->itemCssSelector = '.list-group-item-text .row:nth-child(4) .col-xs-8'; //will select first
        $this->noItemsCssSelector = '.not-existing-class';
    }

    public function test_extract_correct()
    {
        $method = new ExtractElementTextPattern($this->selectorProvider->get($this->itemCssSelector), [
            'pattern' => $this->validCorrectPattern
        ]);
        $value = $method->extract($this->crawler);
        $this->assertEquals('123', $value);
    }

    /**
     * Item is not found. Expecting empty string.
     */
    public function test_extract_empty()
    {
        $method = new ExtractElementTextPattern($this->selectorProvider->get($this->noItemsCssSelector), [
            'pattern' => $this->validCorrectPattern
        ]);
        $value = $method->extract($this->crawler);
        $this->assertEquals('', $value);
    }

    /**
     * Item is found but pattern has no match. Expecting empty string.
     */
    public function test_extract_empty_no_match_pattern()
    {
        $method = new ExtractElementTextPattern($this->selectorProvider->get($this->itemCssSelector), [
            'pattern' => $this->validNoMatchPattern
        ]);
        $value = $method->extract($this->crawler);
        $this->assertEquals('', $value);
    }


    /**
     * Item is found but pattern has no match (wrong param, expects ?P<value>). Expecting empty string.
     */
    public function test_extract_empty_pattern_wrong_param()
    {
        $method = new ExtractElementTextPattern($this->selectorProvider->get($this->itemCssSelector), [
            'pattern' => $this->validPatternWrongParam,
        ]);
        $value = $method->extract($this->crawler);
        $this->assertEquals('', $value);
    }


    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_EXPECTED_ARRAY
     */
    public function test_invalid_params_not_array()
    {
        $params = null;
        new ExtractElementTextPattern($this->selectorProvider->get($this->itemCssSelector), $params);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_EXPECTED_VALUE_MISSING
     */
    public function test_invalid_params_missing_pattern()
    {
        $params = ['some_param'=>'some_value'];
        new ExtractElementTextPattern($this->selectorProvider->get($this->itemCssSelector), $params);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_UNEXPECTED_VALUE
     */
    public function test_invalid_params_unexpected_values()
    {
        $params = ['pattern' => $this->validCorrectPattern, 2, 3];
        new ExtractElementTextPattern($this->selectorProvider->get($this->itemCssSelector), $params);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_INVALID_PATTERN
     */
    public function test_invalid_pattern()
    {
        $params = ['pattern' => $this->invalidPattern];
        new ExtractElementTextPattern($this->selectorProvider->get($this->itemCssSelector), $params);
    }
}
 