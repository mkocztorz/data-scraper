<?php

namespace Mkocztorz\DataScraper\Extractor\Method;


use Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException;
use Symfony\Component\DomCrawler\Crawler;

class ExtractElementTextPattern extends ExtractElementText
{
    /**
     * @param Crawler $crawler
     * @return mixed
     */
    public function extract(Crawler $crawler)
    {
        $value = parent::extract($crawler);
        return $this->matchPattern($value, $this->getProperty('pattern'));
    }

    protected function guardAgainstInvalidProperties()
    {
        $properties = $this->getProperties();
        $this->guardAgainstParamsNotArray();
        $this->guardAgainstMissingProperty('pattern');

        $this->guardAgainstInvalidPattern();

        /*
         * Params have to be empty array
         */
        if (count($properties) != 1) {
            throw new InvalidPropertiesRuntimeException(
                "Properties for ExtractElementText contains unexpected value",
                InvalidPropertiesRuntimeException::CODE_UNEXPECTED_VALUE
            );
        }
    }

}
