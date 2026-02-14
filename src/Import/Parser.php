<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Import;

use Aazsamir\Pamily\Data\Data;

interface Parser
{
    public function parse(string $filepath): ?Data;
}