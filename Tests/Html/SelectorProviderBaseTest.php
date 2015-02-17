<?php

namespace Mkocztorz\DataScraper\Tests\Html;


use Mkocztorz\DataScraper\Html\SelectorInterface;
use Mkocztorz\DataScraper\Html\SelectorProviderBase;
use Mkocztorz\DataScraper\Tests\PHPUnitTestCase;


class SelectorProviderBaseTest extends PHPUnitTestCase
{
    /**
     * @var SelectorProviderBase
     */
    protected $selectorProvider;

    /**
     * @var string
     */
    protected $packagedCssSelectorClass;

    /**
     * @var string
     */
    protected $instantiableClass;

    /**
     * @var string
     */
    protected $abstractClass;

    public function setUp()
    {
        $this->selectorProvider = new SelectorProviderBase();
        $this->packagedCssSelectorClass = '\Mkocztorz\DataScraper\Html\Selector\Css';
        $this->instantiableClass = '\Mkocztorz\DataScraper\Tests\Resources\Classes\SomeInstantiableClass';
        $this->abstractClass = '\Mkocztorz\DataScraper\Tests\Resources\Classes\SomeAbstractClass';
    }

    protected function setUpRegisterSelectors()
    {
        $this->selectorProvider->registerSelector('css', $this->packagedCssSelectorClass, true);
        $this->selectorProvider->registerSelector('css-other', $this->packagedCssSelectorClass);
    }

/*
 * Setting Default selector type
 */

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException
     * @expectedExceptionCode \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException::CODE_NO_SELECTORS_REGISTERED
     */
    public function test_get_without_configuration()
    {
        $this->selectorProvider->get('ul li');
    }


    public function test_default_type_set_auto()
    {
        $type = 'css';
        $this->selectorProvider->registerSelector($type, $this->packagedCssSelectorClass); //automatically set default
        $this->assertSame($this->selectorProvider->getDefaultType(), $type);
    }

    public function test_default_type_set_auto_multiple()
    {
        $type = 'css';
        $type2 = 'css-other';
        $this->selectorProvider->registerSelector($type, $this->packagedCssSelectorClass); //auto set
        $this->selectorProvider->registerSelector($type2, $this->packagedCssSelectorClass);
        $this->assertSame($this->selectorProvider->getDefaultType(), $type);
    }

    public function test_default_type_set_explicit_on_first_register()
    {
        $type = 'css';
        $this->selectorProvider->registerSelector($type, $this->packagedCssSelectorClass, true); //explicit set default (first register)
        $this->assertSame($this->selectorProvider->getDefaultType(), $type);
    }


    public function test_default_type_set_explicit_with_multiple_registers()
    {
        $type = 'css';
        $type2 = 'css-other';
        $this->selectorProvider->registerSelector($type, $this->packagedCssSelectorClass, true); //explicit set default (first register)
        $this->selectorProvider->registerSelector($type2, $this->packagedCssSelectorClass);
        $this->assertSame($this->selectorProvider->getDefaultType(), $type);
    }

    public function test_default_type_set_explicit_on_second_register()
    {
        $type = 'css';
        $type2 = 'css-other';
        $this->selectorProvider->registerSelector($type, $this->packagedCssSelectorClass); //explicit set default (first register)
        $this->selectorProvider->registerSelector($type2, $this->packagedCssSelectorClass, true);
        $this->assertSame($this->selectorProvider->getDefaultType(), $type2);
    }

/*
 * Setting selector classes
 */

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException
     * @expectedExceptionCode \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException::CODE_UNKNOWN_TYPE_REQUESTED
     */
    public function test_get_wrong_selector_type()
    {
        $this->selectorProvider->registerSelector('css', $this->packagedCssSelectorClass, true);
        $this->selectorProvider->get('ul li', 'some-selector'); //css is default, some-selector doesn't exist
    }


    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException
     * @expectedExceptionCode \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException::CODE_NOT_EXISTING_CLASS
     */
    public function test_try_registering_not_existing_class()
    {
        $this->selectorProvider->registerSelector('css', "SomeClassThatDoesntExist", true);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException
     * @expectedExceptionCode \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException::CODE_SELECTOR_INTERFACE_NOT_IMPLEMENTED
     */
    public function test_try_registering_and_using_wrong_type_class()
    {
        $this->selectorProvider->registerSelector('css', $this->instantiableClass, true);
    }

    /**
     * @expectedException \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException
     * @expectedExceptionCode \Mkocztorz\DataScraper\Exception\InvalidSelectorProviderConfigurationRuntimeException::CODE_NOT_INSTANTIABLE_CLASS
     */
    public function test_try_registering_and_using_abstract_class()
    {
        $this->selectorProvider->registerSelector('css', $this->abstractClass, true);
    }

/*
 * Testing the returned selectors
 */

    /**
     * It's actually the main test.
     */
    public function test_if_same_instance_is_returned()
    {
        $this->setUpRegisterSelectors();
        $selector1 = $this->selectorProvider->get('ul li');
        $selector2 = $this->selectorProvider->get('ul li');
        $selector3 = $this->selectorProvider->get('a');
        $this->assertSame($selector1, $selector2);
        $this->assertNotSame($selector1, $selector3);
        $this->assertTrue($selector1 instanceof SelectorInterface);
        $this->assertTrue($selector3 instanceof SelectorInterface);

        $selector4 = $this->selectorProvider->get('ul li', 'css-other');
        $this->assertNotSame($selector1, $selector4); //not same cause from different selectors
        $this->assertTrue($selector4 instanceof SelectorInterface);
    }
}
 