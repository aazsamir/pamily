<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Import\Error;

class LoaderError extends \RuntimeException
{
    public function __construct(string $message = 'Loader error occurred')
    {
        return parent::__construct($message);
    }

    public static function onLine(string $line, int $lineNumber, ?string $message = null): self
    {
        $msg = "Error parsing line $lineNumber: $line";

        if ($message) {
            $msg .= " - $message";
        }

        return new self($msg);
    }
}