<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Import;

use Aazsamir\Pamily\Data\Data;
use Aazsamir\Pamily\Format;
use Aazsamir\Pamily\Import\Error\FileNotFoundException;
use Aazsamir\Pamily\Import\Gedcom\GedcomLoader;
use Aazsamir\Pamily\Import\Gedcom\GedcomParser;

class GenericParser implements Parser
{
    public function parse(string $filepath): ?Data
    {
        if (!file_exists($filepath)) {
            throw new FileNotFoundException($filepath);
        }

        $format = Format::fromFilePath($filepath);

        return match ($format) {
            Format::GEDCOM => (new GedcomParser(new GedcomLoader()))->parse($filepath),
        };
    }
}
