<?php
namespace Mkocztorz\DataScraper\Tests;


class PHPUnitTestCase extends \PHPUnit_Framework_TestCase
{
    protected function getResourcesDir()
    {
        return realpath(__DIR__) . '/Resources/PlainHtml/';
    }
}
