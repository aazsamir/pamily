<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Data;

class Data
{
    public function __construct(
        public Info $info,
        public Tree $tree,
    ) {}
}