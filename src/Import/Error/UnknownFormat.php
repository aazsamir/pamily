<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Import\Error;

class UnknownFormat extends \Exception
{
    public function __construct(string $format)
    {
        parent::__construct("Unknown format: $format");
    }
}