<?php
namespace Mkocztorz\DataScraper\Extractor\Method;

use Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException;
use Mkocztorz\DataScraper\Extractor\ExtractionMethod;
use Symfony\Component\DomCrawler\Crawler;

class ExtractElementText extends ExtractionMethod
{
    /**
     * @param Crawler $crawler
     * @return mixed
     */
    public function extract(Crawler $crawler)
    {
        $element = $this->getFirstElement($crawler);
        return trim($element->text());
    }

    protected function guardAgainstInvalidProperties()
    {
        $properties = $this->getProperties();
        $this->guardAgainstParamsNotArray();

        /*
         * Params have to be empty array
         */
        if (count($properties)>0) {
            throw new InvalidPropertiesRuntimeException(
                "Properties for ExtractElementText should be empty array",
                InvalidPropertiesRuntimeException::CODE_UNEXPECTED_VALUE
            );
        }
    }
}
