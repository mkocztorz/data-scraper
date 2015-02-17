<?php
namespace Mkocztorz\DataScraper\Extractor\Method;

use Mkocztorz\DataScraper\Exception\InvalidPropertiesRuntimeException;
use Mkocztorz\DataScraper\Extractor\ExtractionMethod;
use Mkocztorz\DataScraper\Extractor\ExtractionMethodInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class ExtractListMethod
 * @package Mkocztorz\DataScraper\Extractor\Method
 *
 * Extract a list of data. For each element returned by selector use ExtractItemMethod
 * to get it's data.
 */
class ExtractList extends ExtractionMethod
{
    /**
     * @param Crawler $crawler
     * @return mixed
     */
    public function extract(Crawler $crawler)
    {
        $list = [];
        $elements = $this->getElements($crawler);
        $properties = $this->getProperties();
        foreach ($elements as $element) {
            $item = [];
            /** @var ExtractionMethod $extractor */
            foreach ($properties as $key => $extractor) {
                $item[$key] = $extractor->extract($element);
            }
            $list[] = $item;
        }
        return $list;
    }

    protected function guardAgainstInvalidProperties()
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

        /*
         * It has to contain only objects implementing ExtractionMethodInterface.
         */
        foreach ($properties as $key => $property) {
            if (!($property instanceof ExtractionMethodInterface)) {
                throw new InvalidPropertiesRuntimeException(
                    "ExtractList expects only ExtractionMethodInterface as parameters",
                    InvalidPropertiesRuntimeException::CODE_EXPECTED_ONLY_EXTRACTION_METHOD_INTERFACE
                );
            }
        }
    }
}
