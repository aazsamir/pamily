<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Import\Gedcom;

use Aazsamir\Pamily\GedcomTag;
use Aazsamir\Pamily\Import\Error\LoaderError;

class GedcomLoader
{
    /**
     * @return GedcomLine[]
     */
    public function load(string $filepath): array
    {
        $data = $this->loadData($filepath);
        $this->merge($data);

        return $data;
    }

    /**
     * @return GedcomLine[]
     */
    public function loadTree(string $filepath): array
    {
        $data = $this->load($filepath);
        $hierarchy = $this->hierarchize($data);

        return $hierarchy;
    }

    /**
     * @return GedcomLine[]
     */
    private function loadData(string $filepath): array
    {
        $data = [];

        foreach ($this->iterate($filepath) as $lineNumber => $line) {
            // <level> [<xref_id>] <tag> [<value>]
            [$level, $xrefId, $tag, $value] = $this->tokenize($line, $lineNumber);
            $data[] = new GedcomLine(
                level: $level,
                xrefId: $xrefId,
                tag: $tag,
                value: $value,
            );
        }

        return $data;        
    }

    /**
     * @return \Generator<int, string>
     */
    private function iterate(string $filepath): \Generator
    {
        $handle = fopen($filepath, 'r');

        if ($handle === false) {
            throw new \RuntimeException("Failed to open file: $filepath");
        }

        try {
            while (($line = fgets($handle)) !== false) {
                yield trim($line);
            }
        } finally {
            fclose($handle);
        }
    }

    private function tokenize(string $line, int $lineNumber): array
    {
        $level = null;
        $xrefId = null;
        $tag = null;
        $value = null;

        $parts = preg_split('/\s+/', $line, 4);

        // Level is always the first part
        $firstPart = $parts[0] ?? null;

        if (!\is_numeric($firstPart)) {
            throw LoaderError::onLine($line, $lineNumber, 'Invalid level');
        }

        $level = (int) $firstPart;

        $secondPart = $parts[1] ?? null;

        if (str_starts_with($secondPart ?? '', '@') && str_ends_with($secondPart ?? '', '@')) {
            $xrefId = trim($secondPart, '@');
            $tag = $parts[2] ?? null;
            $value = $parts[3] ?? null;
        } else {
            $tag = $secondPart;
            $value = \preg_split('/\s+/', $line, 3)[2] ?? null;
        }

        if ($tag === null) {
            throw LoaderError::onLine($line, $lineNumber, 'Missing tag');
        }

        $tag = GedcomTag::from($tag);

        if (\is_string($value)) {
            $value = trim($value, "\n\r\t\v\0 @");
        }

        return [$level, $xrefId, $tag, $value];
    }

    /**
     * @param GedcomLine[] $data
     */
    private function merge(array &$data): void
    {
        // merge CONT and CONC lines with their parent line
        for ($i = count($data) - 1; $i >= 0; $i--) {
            $line = $data[$i];
            if ($line->tag === GedcomTag::CONT) {
                $data[$i - 1]->value .= "\n" . $line->value;
                array_splice($data, $i, 1);
            } elseif ($line->tag === GedcomTag::CONC) {
                $data[$i - 1]->value .= $line->value;
                array_splice($data, $i, 1);
            }
        }
    }

    /**
     * @param GedcomLine[] $data
     * 
     * @return GedcomLine[]
     */
    private function hierarchize(array $data): array
    {
        $records = [];
        $levelingStack = [];

        foreach ($data as &$line) {
            if ($line->level === 0) {
                $records[] = $line;
                $levelingStack = [0 => $line];
            } else {
                $parentLevel = $line->level - 1;
                $parent = $levelingStack[$parentLevel];
                $parent->children[] = $line;
                $levelingStack[$line->level] = $line;
            }
        }

        return $records;
    }
}