<?php

namespace Mkocztorz\DataScraper\Extractor;


use Mkocztorz\DataScraper\Html\SelectorProviderBase;


class ExtractorBase
{
    /**
     * @var SelectorProviderBase
     */
    protected $selectorProvider;

    /**
     * @var array
     */
    protected $registeredMethods;

    public function __construct(SelectorProviderBase $selectorProvider)
    {
        $this->selectorProvider = $selectorProvider;
        $this->registeredMethods = [];
    }

    /**
     * @param string $method Get method suffix ($method='list' -> $this->getList)
     * @param string $class DataExtractorInterface class name
     */
    public function registerMethod($method, $class)
    {
        $this->registeredMethods[$method] = $class;
    }

    /**
     * @param string $method Registered extract method
     * @param string $selectorText Selector string
     * @param string $selectorType
     * @param array $params
     * @return ExtractionMethod
     */
    public function get($method, $selectorText = '', $selectorType = null, $params = [])
    {
        $class = $this->registeredMethods[$method];
        $selector = $this->selectorProvider->get($selectorText, $selectorType);
        return new $class($selector, $params);
    }

    /**
     * Converting calls like getAttribute('div', ['attribute'=>'foo']) to get('attribute', 'div', ['attribute'=>'foo'])
     *
     * @param $name
     * @param $parameters
     * @return ExtractionMethod
     */
    public function __call($name, $parameters)
    {
        $method = lcfirst(str_replace("get", "", $name));
        $selectorText = isset($parameters[0]) ? $parameters[0] : '';
        $params = isset($parameters[1]) ? $parameters[1] : [];
        $type = null;
        if (is_array($selectorText)) {
            reset($selectorText);
            $type = key($selectorText);
            $selector = current($selectorText);
        }
        else {
            $selector = $selectorText;
        }
        return $this->get($method, $selector, $type, $params);
    }

    /**
     * @return SelectorProviderBase
     */
    public function getSelectorProvider()
    {
        return $this->selectorProvider;
    }
}