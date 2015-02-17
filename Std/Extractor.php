<?php

namespace Mkocztorz\DataScraper\Std;

use Mkocztorz\DataScraper\Extractor\ExtractionMethodInterface;
use Mkocztorz\DataScraper\Extractor\ExtractorBase;

/**
 * Class Extractor
 * @package Mkocztorz\DataScraper\Extractor
 *
 * This is a shortcut class to Extractor class. Registers all default extract methods shipped with lib.
 * To extend use this class or Extractor.
 *
 * @method ExtractionMethodInterface getText
 * @method ExtractionMethodInterface getList
 * @method ExtractionMethodInterface getAttribute
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
    }
} 