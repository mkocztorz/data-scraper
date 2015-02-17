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
        $properties = $this->getProperties();
        return $this->getAttributeValue($element, $properties['attr']);
    }

    protected function guardAgainstInvalidProperties()
    {
        $properties = $this->getProperties();
        $this->guardAgainstParamsNotArray();

        if (!isset($properties['attr'])) {
            throw new InvalidPropertiesRuntimeException(
                "The property 'attr' is missing",
                InvalidPropertiesRuntimeException::CODE_EXPECTED_VALUE_MISSING
            );
        }

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
