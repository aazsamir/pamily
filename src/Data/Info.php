<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Data;

class Info
{
    public function __construct(
        public string $name,
        public string $version,
        public \DateTimeInterface $date,
    ) {}
}
