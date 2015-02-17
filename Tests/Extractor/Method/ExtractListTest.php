<?php

namespace Mkocztorz\DataScraper\Tests\Extractor\Method;

use Mkocztorz\DataScraper\Extractor\Method\ExtractList;
use Mkocztorz\DataScraper\Std\SelectorProvider;
use Mkocztorz\DataScraper\Tests\PHPUnitTestCase;
use Symfony\Component\DomCrawler\Crawler;

class ExtractListTest extends PHPUnitTestCase
{
    /**
     * @var string
     */
    protected $itemsCssSelector;
    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var SelectorProvider
     */
    protected $selectorProvider;

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
        $this->itemsCssSelector = '.list-group .list-group-item';
        $this->noItemsCssSelector = '.not-existing-class';
    }

    public function test_create_list_extractor()
    {
        $method = new ExtractList($this->selectorProvider->get($this->itemsCssSelector), []);
        $this->assertTrue($method instanceof ExtractList);
    }

    public function test_extract_correct()
    {
        /*
         * We are expecting each mock's extract to be called exctly 2 times cause the crawler contains html with
         * two DOM elements responding to the css selector.
         */
        $nameMock = $this->getExtractionMethodMock();
        $name1 = 'Name 1';
        $name2 = 'Name 2';
        $age1 = 25;
        $age2 = 30;
        $nameMock
            ->expects($this->at(0))
            ->method('extract')
            ->willReturn($name1);
        $nameMock
            ->expects($this->at(1))
            ->method('extract')
            ->willReturn($name2);
        $nameMock
            ->expects($this->exactly(2))
            ->method('extract');

        $ageMock = $this->getExtractionMethodMock();
        $ageMock
            ->expects($this->at(0))
            ->method('extract')
            ->willReturn($age1);
        $ageMock
            ->expects($this->at(1))
            ->method('extract')
            ->willReturn($age2);
        $ageMock
            ->expects($this->exactly(2))
            ->method('extract');

        $mockChildExtractors = [
            'name'  => $nameMock,
            'age'  => $ageMock,
        ];

        $method = new ExtractList($this->selectorProvider->get($this->itemsCssSelector), $mockChildExtractors);
        $returned = $method->extract($this->crawler);

        $this->assertTrue(is_array($returned));
        $this->assertCount(2, $returned);
        $this->assertTrue($returned[0]['name'] == $name1);
        $this->assertTrue($returned[1]['name'] == $name2);
        $this->assertTrue($returned[0]['age'] == $age1);
        $this->assertTrue($returned[1]['age'] == $age2);
    }

    public function test_extract_empty()
    {
        $nameMock = $this->getExtractionMethodMock();
        $nameMock
            ->expects($this->never())
            ->method('extract');

        $ageMock = $this->getExtractionMethodMock();
        $ageMock
            ->expects($this->never())
            ->method('extract');

        $mockChildExtractors = [
            'name'  => $nameMock,
            'age'  => $ageMock,
        ];

        $method = new ExtractList($this->selectorProvider->get($this->noItemsCssSelector), $mockChildExtractors);
        $returned = $method->extract($this->crawler);

        $this->assertTrue(is_array($returned));
        $this->assertCount(0, $returned);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_EXPECTED_ARRAY
     */
    public function test_invalid_params_not_array()
    {
        $params = null;
        new ExtractList($this->selectorProvider->get($this->itemsCssSelector), $params);
    }


    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException
     * @expectedExceptionCode Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException::CODE_EXPECTED_ONLY_EXTRACTION_METHOD_INTERFACE
     */
    public function test_invalid_params_not_extraction_methods()
    {
        $params = [
            'age'  => $this->getExtractionMethodMock(),
            'name' => new \stdClass(), //causes exception
        ];
        new ExtractList($this->selectorProvider->get($this->itemsCssSelector), $params);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    protected function getExtractionMethodMock()
    {
        return $this->getMockBuilder('\Mkocztorz\DataScraper\Extractor\ExtractionMethodInterface')->disableOriginalConstructor()->getMock();
    }
}
 