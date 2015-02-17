<?php
namespace Mkocztorz\DataScraper\Extractor;

use Symfony\Component\DomCrawler\Crawler;

interface ExtractionMethodInterface
{
    /**
     * @param Crawler $crawler
     * @return mixed
     */
    public function extract(Crawler $crawler);
}