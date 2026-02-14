<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Import\Gedcom;

use Aazsamir\Pamily\GedcomTag;

class GedcomLine
{
    public function __construct(
        public int $level,
        public ?string $xrefId,
        public GedcomTag $tag,
        public ?string $value,
        /** @var GedcomLine[] */
        public array $children = [],
    ) {}

    public function findTag(GedcomTag $tag): ?GedcomLine
    {
        foreach ($this->children as $child) {
            if ($child->tag === $tag) {
                return $child;
            }
        }

        foreach ($this->children as $child) {
            $found = $child->findTag($tag);

            if ($found !== null) {
                return $found;
            }
        }

        return null;
    }

    /** @return GedcomLine[] */
    public function findTags(GedcomTag $tag): array
    {
        $found = [];

        foreach ($this->children as $child) {
            if ($child->tag === $tag) {
                $found[] = $child;
            }
        }

        foreach ($this->children as $child) {
            $found = array_merge($found, $child->findTags($tag));
        }

        return $found;
    }
}