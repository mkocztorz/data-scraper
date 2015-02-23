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
     * @param string $prop
     * @return mixed
     */
    protected function getProperty($prop)
    {
        return $this->properties[$prop];
    }

    /**
     * @param string $prop
     * @return bool
     */
    protected function isProperty($prop)
    {
        return isset($this->properties[$prop]);
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

    /**
     * @param string $propertyName
     */
    protected function guardAgainstMissingProperty($propertyName)
    {
        if (!$this->isProperty($propertyName)) {
            throw new InvalidPropertiesRuntimeException(
                sprintf("The property %s is missing", $propertyName),
                InvalidPropertiesRuntimeException::CODE_EXPECTED_VALUE_MISSING
            );
        }
    }

    /**
     * @param string $value
     * @param string $pattern
     * @return string
     */
    protected function matchPattern($value, $pattern)
    {
        $matches = array();
        preg_match($pattern, $value, $matches);
        return isset($matches['value']) ? $matches['value'] : '';
    }

    protected function guardAgainstInvalidPattern()
    {
        try {
            //just testing the pattern
            $res = preg_match($this->getProperty('pattern'), '');
        }
        catch(\Exception $e) {
            $res = false;
        }

        if ( $res === false) {
            throw new InvalidPropertiesRuntimeException(
                sprintf("Invalid pattern %s", $this->getProperty('pattern')),
                InvalidPropertiesRuntimeException::CODE_INVALID_PATTERN
            );
        }
    }
}
