<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Data;

class Person
{
    /**
     * @param Person[] $parents
     * @param Person[] $children
     * @param Person[] $partners
     */
    public function __construct(
        public ?string $firstName = null,
        public ?string $lastName = null,
        public ?Gender $gender = null,
        public ?\DateTimeInterface $birth = null,
        public array $parents = [],
        public array $children = [],
        public array $partners = [],
    ) {}
}
