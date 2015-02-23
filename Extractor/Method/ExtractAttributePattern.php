<?php

namespace Mkocztorz\DataScraper\Extractor\Method;


use Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException;
use Mkocztorz\DataScraper\Extractor\ExtractionMethod;
use Symfony\Component\DomCrawler\Crawler;

class ExtractAttributePattern extends ExtractionMethod
{
    /**
     * @param Crawler $crawler
     * @return mixed
     */
    public function extract(Crawler $crawler)
    {
        $element = $this->getFirstElement($crawler);
        $attribute = $this->getAttributeValue($element, $this->getProperty('attr'));
        return $this->matchPattern($attribute, $this->getProperty('pattern'));
    }

    protected function guardAgainstInvalidProperties()
    {
        $properties = $this->getProperties();
        $this->guardAgainstParamsNotArray();
        $this->guardAgainstMissingProperty('attr');
        $this->guardAgainstMissingProperty('pattern');
        $this->guardAgainstInvalidPattern();

        /*
         * Params have to be array with 1 value
         */
        if (count($properties)!=2) {
            throw new InvalidPropertiesRuntimeException(
                "Properties for ExtractAttributePattern should contain only 'attr' and 'pattern'",
                InvalidPropertiesRuntimeException::CODE_UNEXPECTED_VALUE
            );
        }
    }
}
