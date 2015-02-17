<?php
namespace Mkocztorz\DataScraper\Html\Selector;

use Mkocztorz\DataScraper\Html\Selector;
use Symfony\Component\DomCrawler\Crawler;

class Css extends Selector
{
    /**
     * @var string
     */
    protected $cssSelector;

    /**
     * @param string $cssSelector
     */
    public function __construct($cssSelector)
    {
        $this->cssSelector = (string)$cssSelector;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    public function filterElements(Crawler $crawler)
    {
        if (!$this->isCssSelectorEmpty()) {
            $elements = $crawler->filter($this->getCssSelector())->each(function ($element) {
                return $element;
            });
        } else {
            $elements[] = $crawler;
        }
        return $elements;
    }

    /**
     * @return bool
     */
    protected function isCssSelectorEmpty()
    {
        return empty($this->cssSelector);
    }

    /**
     * @return string
     */
    public function getCssSelector()
    {
        return $this->cssSelector;
    }
}
