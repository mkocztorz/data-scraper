<?php

namespace Mkocztorz\DataScraper\Extractor\Method;


use Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException;
use Mkocztorz\DataScraper\Extractor\ExtractionMethod;
use Symfony\Component\DomCrawler\Crawler;

class ExtractAttribute extends ExtractionMethod
{
    /**
     * @param Crawler $crawler
     * @return mixed
     */
    public function extract(Crawler $crawler)
    {
        $element = $this->getFirstElement($crawler);
        return $this->getAttributeValue($element, $this->getProperty('attr'));
    }

    protected function guardAgainstInvalidProperties()
    {
        $properties = $this->getProperties();
        $this->guardAgainstParamsNotArray();
        $this->guardAgainstMissingProperty('attr');

        /*
         * Params have to be array with 1 value
         */
        if (count($properties)!=1) {
            throw new InvalidPropertiesRuntimeException(
                "Properties for ExtractAttribute should contain only 'attr'",
                InvalidPropertiesRuntimeException::CODE_UNEXPECTED_VALUE
            );
        }
    }
}
