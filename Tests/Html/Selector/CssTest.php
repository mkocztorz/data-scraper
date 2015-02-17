<?php
namespace Mkocztorz\DataScraper\Tests\Html\Selector;


use Mkocztorz\DataScraper\Html\Selector\Css;
use Mkocztorz\DataScraper\Tests\PHPUnitTestCase;
use Symfony\Component\DomCrawler\Crawler;

class CssTest extends PHPUnitTestCase {
    /**
     * @var Crawler
     */
    protected $crawler;

    /**
     * @var Css
     */
    protected $listSelector;

    /**
     * @var Css
     */
    protected $emptyResultSelector;

    /**
     * @var Css
     */
    protected $emptySelector;

    protected function getValidHtml()
    {
        return file_get_contents($this->getResourcesDir() . 'userlist.html');
    }

    public function setUp()
    {
        $this->listSelector = new Css(".list-group .list-group-item");
        $this->emptyResultSelector = new Css(".non-existing");
        $this->emptySelector = new Css("");
        $this->crawler = new Crawler();
        $this->crawler->addHtmlContent($this->getValidHtml());
    }

    public function test_selecting_elements()
    {
        $this->assertCount(2, $this->listSelector->filterElements($this->crawler));
    }

    public function test_valid_elements_returned()
    {
        $elements = $this->listSelector->filterElements($this->crawler);
        /** @var Crawler $element */
        foreach($elements as $element) {
            $this->assertTrue($element instanceof Crawler);
            $this->assertTrue($element->nodeName() == 'div');
            $this->assertTrue(strpos($element->attr('class'), 'list-group-item') !== false);
        }
        $this->assertTrue($elements[0]->attr('id') == 'user-22', "Wrong DOM element found");
        $this->assertTrue($elements[1]->attr('id') == 'user-99', "Wrong DOM element found");
    }

    public function test_selecting_first_element()
    {
        $element = $this->listSelector->filterFirstElement($this->crawler);
        $this->assertTrue($element instanceof Crawler, "First element filtered from list should be Crawler object");
    }

    public function test_selecting_empty_set()
    {
        $emptyResult = $this->emptyResultSelector->filterElements($this->crawler);
        $this->assertTrue(is_array($emptyResult));
        $this->assertCount(0,$emptyResult);
    }

    public function test_selecting_empty_first_element()
    {
        $noElement = $this->emptyResultSelector->filterFirstElement($this->crawler); //should return same element ($this->crawler)
        $this->assertEquals('empty', $noElement->nodeName());
    }

    public function test_using_empty_selector()
    {
        $list = $this->emptySelector->filterElements($this->crawler); //empty selector should return top level element
        $this->assertCount(1, $list);
        $this->assertTrue($list[0]->nodeName() == 'html');

        $single = $this->emptySelector->filterFirstElement($this->crawler);
        $this->assertTrue($single instanceof Crawler);
        $this->assertTrue($single->nodeName() == 'html');
    }
}
 