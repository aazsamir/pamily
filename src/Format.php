<?php

declare(strict_types=1);

namespace Aazsamir\Pamily;

use Aazsamir\Pamily\Import\Error\UnknownFormat;

enum Format: string
{
    case GEDCOM = 'gedcom';

    public static function fromFilePath(string $path): self
    {
        $extension = pathinfo($path, PATHINFO_EXTENSION);

        return match ($extension) {
            'ged' => self::GEDCOM,
            default => throw new UnknownFormat($extension),
        };
    }
}
