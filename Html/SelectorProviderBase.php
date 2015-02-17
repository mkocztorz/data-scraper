<?php
namespace Mkocztorz\DataScraper\Html;

use Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException;
use ReflectionClass;

/**
 * User may configure a number of selectors to scrape the data from HTML. It is possible that sometimes the selectors
 * will be the same (e.g. scraping text from DOM element then scraping some attributes from it). User could make
 * new instance of an object implementing SelectorInterface but it would mean some redundancy. That's where the
 * SelectorProviderBase comes in as a Factory for objects implementing SelectorInterface.
 * When configuring SelectorProviderBase object user can register a number of selectors (of which one may be a default).
 * It's done by using registerSelector method.
 * Then user may request Selector objects by calling get method.
 *
 * SelectorProviderBase will create only one object with same selectorString for every selector type.
 *
 * If you call:
 * $selector1 = $selectorProvider->get('ul li');
 * $selector2 = $selectorProvider->get('ul li');
 * You get same instance of selector object.
 *
 *
 * Class SelectorProviderBase
 * @package Mkocztorz\DataScraper\Html
 */
class SelectorProviderBase
{
    protected $cache = [];
    protected $selectors = [];
    protected $defaultSelector;

    /**
     * @param string $selectorString
     * @param string $type
     * @return SelectorInterface
     */
    public function get($selectorString, $type = null)
    {
        $type = is_null($type) ? $this->defaultSelector : $type;
        $key = $this->getKey($selectorString, $type);
        if (!isset($this->cache[$key])) {
            $className = $this->getSelectorClass($type);
            $selector = $selector = new $className($selectorString);
            $this->cache[$key] = $selector;
        }

        return $this->cache[$key];
    }

    protected function getKey($selectorString, $type = 'css')
    {
        return $type . "|" . md5($selectorString);
    }

    public function registerSelector($type, $class, $default = false)
    {
        $this->guardAgainstInvalidClassRegistration($class);
        $this->selectors[$type] = $class;
        $this->defaultSelector = $default ? $type : $this->defaultSelector;
        //set first one as default regardless of $default param
        $this->defaultSelector = is_null($this->defaultSelector) ? $type : $this->defaultSelector;
    }

    /**
     * @return string|null
     */
    public function getDefaultType()
    {
        return $this->defaultSelector;
    }

    /**
     * @param string $type
     * @return string
     */
    protected function getSelectorClass($type)
    {
        $selectorType = null;
        $this->guardAgainstNoSelectorsRegistered();
        if (isset($this->selectors[$type])) {
            $selectorType = $this->selectors[$type];
        }
        else {
            $this->guardAgainstRequestingInvalidSelectorType($type);
        }
        return $selectorType;
    }

    protected function guardAgainstNoSelectorsRegistered()
    {
        if (count($this->selectors) == 0) {
            throw new InvalidSelectorProviderConfigurationRuntimeException(
                "No selectors registered in SelectorProviderBase",
                InvalidSelectorProviderConfigurationRuntimeException::CODE_NO_SELECTORS_REGISTERED
            );
        }
    }

    /**
     * @param $type
     */
    protected function guardAgainstRequestingInvalidSelectorType($type)
    {
        throw new InvalidSelectorProviderConfigurationRuntimeException(
            sprintf("No selector with %s type is registered in SelectorProviderBase", $type),
            InvalidSelectorProviderConfigurationRuntimeException::CODE_UNKNOWN_TYPE_REQUESTED
        );
    }

    /**
     * @param string $class
     */
    protected function guardAgainstRegisteringNotExistingClass($class)
    {
        if (!class_exists($class)) {
            throw new InvalidSelectorProviderConfigurationRuntimeException(
                sprintf("The class %s can't be registered. It doesn't exist.", $class),
                InvalidSelectorProviderConfigurationRuntimeException::CODE_NOT_EXISTING_CLASS
            );
        }
    }

    /**
     * @param string $class
     */
    private function guardAgainstInvalidClassRegistration($class)
    {
        $this->guardAgainstRegisteringNotExistingClass($class);

        $reflection = new ReflectionClass($class);
        if (!$reflection->isInstantiable()) {
            throw new InvalidSelectorProviderConfigurationRuntimeException(
                sprintf("Registered class %s is not instantiable", $class),
                InvalidSelectorProviderConfigurationRuntimeException::CODE_NOT_INSTANTIABLE_CLASS
            );
        }

        if (!$reflection->implementsInterface('\Mkocztorz\DataScraper\Html\SelectorInterface')) {
            throw new InvalidSelectorProviderConfigurationRuntimeException(
                sprintf("Selector class %s should implement SelectorInterface", $class),
                InvalidSelectorProviderConfigurationRuntimeException::CODE_SELECTOR_INTERFACE_NOT_IMPLEMENTED
            );
        }
    }

}
