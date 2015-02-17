<?php
namespace Mkocztorz\DataScraper\Html;

use Symfony\Component\DomCrawler\Crawler;

interface SelectorInterface
{
    /**
     * @param Crawler $crawler
     * @return array
     */
    public function filterElements(Crawler $crawler);

    /**
     * @param Crawler $crawler
     * @return Crawler
     */
    public function filterFirstElement(Crawler $crawler);
}
