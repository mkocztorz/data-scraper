<?php

namespace Mkocztorz\DataScraper\Std;

use Mkocztorz\DataScraper\Html\SelectorProviderBase;

class SelectorProvider extends SelectorProviderBase
{
    public function __construct()
    {
        $this->registerSelector('css', '\Mkocztorz\DataScraper\Html\Selector\Css', true);
    }
} 