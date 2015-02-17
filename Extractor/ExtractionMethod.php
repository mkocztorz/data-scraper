<?php

namespace Mkocztorz\DataScraper\Extractor;


use Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException;
use Mkocztorz\DataScraper\Html\SelectorInterface;
use Symfony\Component\DomCrawler\Crawler;

abstract class ExtractionMethod implements ExtractionMethodInterface
{
    /**
     * @var SelectorInterface
     */
    protected $elementsSelector;

    /**
     * @var array
     */
    protected $properties;

    /**
     * @param SelectorInterface $elementsSelector
     * @param array $properties
     */
    public function __construct(SelectorInterface $elementsSelector, $properties = [])
    {
        $this->elementsSelector = $elementsSelector;
        $this->properties = $properties;
        $this->guardAgainstInvalidProperties();
    }

    /**
     * @return SelectorInterface
     */
    protected function getSelector()
    {
        return $this->elementsSelector;
    }

    /**
     * @return array
     */
    protected function getProperties()
    {
        return $this->properties;
    }

    /**
     * @param Crawler $crawler
     * @return Crawler
     */
    protected function getFirstElement(Crawler $crawler)
    {
        return $this->getSelector()->filterFirstElement($crawler);
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    protected function getElements(Crawler $crawler)
    {
        return $this->getSelector()->filterElements($crawler);
    }


    /**
     * @param Crawler $element
     * @param $attributeName
     * @return null|string
     */
    protected function getAttributeValue(Crawler $element, $attributeName)
    {
        $attributeValue = $element->attr($attributeName);
        return is_null($attributeValue) ? "" : trim($attributeValue);
    }

    /**
     * Check properties and throw an exception if anything is wrong.
     * Override in child classes
     */
    protected function guardAgainstInvalidProperties()
    {
    }

    /**
     * @throws InvalidPropertiesRuntimeException
     */
    protected function guardAgainstParamsNotArray()
    {
        $properties = $this->getProperties();

        /*
         * It has to be an array.
         */
        if (!is_array($properties)) {
            throw new InvalidPropertiesRuntimeException(
                "Paremeters should be an array",
                InvalidPropertiesRuntimeException::CODE_EXPECTED_ARRAY
            );
        }
    }
}