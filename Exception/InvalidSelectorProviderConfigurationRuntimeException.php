<?php
namespace Mkocztorz\DataScraper\Exception;

use RuntimeException;

class InvalidSelectorProviderConfigurationRuntimeException extends RuntimeException
{
    const CODE_NO_SELECTORS_REGISTERED = 1;
    const CODE_UNKNOWN_TYPE_REQUESTED = 2;
    const CODE_NOT_EXISTING_CLASS = 3;
    const CODE_NOT_INSTANTIABLE_CLASS = 4;
    const CODE_SELECTOR_INTERFACE_NOT_IMPLEMENTED = 5;
}