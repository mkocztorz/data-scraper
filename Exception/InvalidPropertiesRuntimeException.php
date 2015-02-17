<?php

namespace Mkocztorz\DataScraper\Exception;

use RuntimeException;

class InvalidPropertiesRuntimeException extends RuntimeException
{
    const CODE_EXPECTED_ARRAY = 1;
    const CODE_EXPECTED_ONLY_EXTRACTION_METHOD_INTERFACE = 2;
    const CODE_EXPECTED_VALUE_MISSING = 3;
    const CODE_UNEXPECTED_VALUE = 4;
}
