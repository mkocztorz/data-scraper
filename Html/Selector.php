<?php
namespace Mkocztorz\DataScraper\Html;

use Symfony\Component\DomCrawler\Crawler;

abstract class Selector implements SelectorInterface
{
    /**
     * @param Crawler $crawler
     * @return Crawler
     */
    public function filterFirstElement(Crawler $crawler)
    {
        $elements = $this->filterElements($crawler);
        if (count($elements) > 0 && ($elements[0] instanceof Crawler)) {
            $firstElement = $elements[0];
        }
        else {
            $firstElement = $crawler->filterXPath(''); //trick to get empty instance of crawler
            $firstElement->addContent('<empty></empty>'); //creating a fake, empty element to allow all node operations
            $firstElement = $firstElement->filter('empty'); //otherwise the node is 'html' automagically added
        }

        return $firstElement;
    }
}
