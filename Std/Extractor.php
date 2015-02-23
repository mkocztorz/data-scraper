<?php

namespace Mkocztorz\DataScraper\Std;

use Mkocztorz\DataScraper\Extractor\ExtractorBase;

/**
 * Class Extractor
 * @package Mkocztorz\DataScraper\Extractor
 *
 * This is a shortcut class to Extractor class. Registers all default extract methods shipped with lib.
 * To extend use this class or Extractor.
 *
 * @method \Mkocztorz\DataScraper\Extractor\ExtractionMethodInterface getText
 * @method \Mkocztorz\DataScraper\Extractor\ExtractionMethodInterface getList
 * @method \Mkocztorz\DataScraper\Extractor\ExtractionMethodInterface getAttribute
 * @method \Mkocztorz\DataScraper\Extractor\ExtractionMethodInterface getTextPattern
 * @method \Mkocztorz\DataScraper\Extractor\ExtractionMethodInterface getAttributePattern
 */
class Extractor extends ExtractorBase
{
    public function __construct()
    {
        $selectorProvider = new SelectorProvider();
        parent::__construct($selectorProvider);
        $this->registerMethod('text', '\Mkocztorz\DataScraper\Extractor\Method\ExtractElementText');
        $this->registerMethod('list', '\Mkocztorz\DataScraper\Extractor\Method\ExtractList');
        $this->registerMethod('attribute', '\Mkocztorz\DataScraper\Extractor\Method\ExtractAttribute');
        $this->registerMethod('textPattern', '\Mkocztorz\DataScraper\Extractor\Method\ExtractElementTextPattern');
        $this->registerMethod('attributePattern', '\Mkocztorz\DataScraper\Extractor\Method\ExtractAttributePattern');
    }
}
