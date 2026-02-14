<?php

declare(strict_types=1);

namespace Aazsamir\Pamily\Import\Gedcom;

use Aazsamir\Pamily\Data\Data;
use Aazsamir\Pamily\Data\Gender;
use Aazsamir\Pamily\Data\Info;
use Aazsamir\Pamily\Data\Person;
use Aazsamir\Pamily\Data\Tree;
use Aazsamir\Pamily\GedcomTag;
use Aazsamir\Pamily\Import\Parser;

class GedcomParser implements Parser
{
    public function __construct(
        private GedcomLoader $gedcomLoader,
    ) {}

    public function parse(string $filepath): ?Data
    {
        $hierarchy = $this->gedcomLoader->loadTree($filepath);

        $info = null;
        /** @var Person[] $persons */
        $persons = [];
        /** @var array<string, array{husband: string|null, wife: string|null, children: string[]}> $families */
        $families = [];
        /** @var array<string, string[]> $partners */
        $debug = [];

        foreach ($hierarchy as $record) {
            if ($record->tag === GedcomTag::HEAD) {
                $info = $this->parseInfo($record);

                continue;
            }

            if ($record->tag === GedcomTag::INDI) {
                [$person, $family] = $this->parsePerson($record);
                $persons[$record->xrefId] = $person;

                continue;
            }

            if ($record->tag === GedcomTag::FAM) {
                $families[$record->xrefId] = $this->parseFamily($record);

                continue;
            }

            if ($record->tag) {
                $debug[$record->tag->value] = $record->tag->value;
            }
        }

        foreach ($families as $family) {
            $husband = $family['husband'];
            $wife = $family['wife'];
            $children = $family['children'];

            if ($husband) {
                $husband = $persons[$husband] ?? null;
            } else {
                $husband = null;
            }

            if ($wife) {
                $wife = $persons[$wife] ?? null;
            } else {
                $wife = null;
            }

            $children = array_map(fn($child) => $persons[$child] ?? null, $children);
            $children = array_filter($children, fn($child) => $child !== null);

            foreach ($children as $child) {
                if ($husband) {
                    $husband->children[] = $child;
                    $child->parents[] = $husband;
                }

                if ($wife) {
                    $wife->children[] = $child;
                    $child->parents[] = $wife;
                }
            }

            if ($husband && $wife) {
                $husband->partners[] = $wife;
                $wife->partners[] = $husband;
            }
        }

        return new Data(
            info: $info,
            tree: new Tree(array_values($persons)),
        );
    }

    private function parseInfo(GedcomLine $record): Info
    {
        $name = $record->findTag(GedcomTag::NAME);
        $version = $record->findTag(GedcomTag::VERS);
        $date = $record->findTag(GedcomTag::DATE);

        return new Info(
            name: $name?->value ?? 'default',
            version: $version?->value ?? 'default',
            date: $date?->value ? new \DateTimeImmutable($date->value) : new \DateTimeImmutable(),
        );
    }

    /**
     * @return array{Person, string|null}
     */
    private function parsePerson(GedcomLine $record): array
    {
        // TODO: handle multiple names, given, married etc
        $name = $record->findTag(GedcomTag::NAME);
        $firstName = $name?->findTag(GedcomTag::GIVN);
        $lastName = $name?->findTag(GedcomTag::SURN);
        $gender = $record->findTag(GedcomTag::SEX);
        $birth = $record->findTag(GedcomTag::BIRT)?->findTag(GedcomTag::DATE);
        $family = $record->findTag(GedcomTag::FAMC);

        $person = new Person(
            firstName: $firstName?->value,
            lastName: $lastName?->value,
            gender: match ($gender?->value) {
                'M' => Gender::MALE,
                'F' => Gender::FEMALE,
                default => null,
            },
            birth: $birth?->value ? new \DateTimeImmutable($birth->value) : null,
        );

        $family = $family?->value;

        return [$person, $family];
    }

    /**
     * @return array{husband: string|null, wife: string|null, children: string[]}
     */
    private function parseFamily(GedcomLine $record): array
    {
        $husband = $record->findTag(GedcomTag::HUSB)?->value;
        $wife = $record->findTag(GedcomTag::WIFE)?->value;
        $children = $record->findTags(GedcomTag::CHIL);

        $children = array_map(fn(GedcomLine $child) => $child->value, $children);

        return ['husband' => $husband, 'wife' => $wife, 'children' => $children];
    }
}
