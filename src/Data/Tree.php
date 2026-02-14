<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Data;

class Tree
{
    /**
     * @param Person[] $persons
     */
    public function __construct(
        public array $persons,
    ) {}
}
