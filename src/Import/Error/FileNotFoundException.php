<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Import\Error;

class FileNotFoundException extends \Exception
{
    public function __construct(string $filepath)
    {
        parent::__construct("File not found: {$filepath}");
    }
}
