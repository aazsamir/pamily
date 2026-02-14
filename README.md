# Pamily

Pamily is a PHP library for managing family trees and genealogical data.

Supported formats:
- GEDCOM

## Installation

You can install Pamily using Composer:

```bash
composer require aazsamir/pamily
```

## Usage

Parse a GEDCOM to internal `Aazsamir\Pamily\Data\Data` structure:

```php
<?php
$parser = new Aazsamir\Pamily\Import\GenericParser();
$data = $parser->parse('path/to/your/file.ged');
```

RAW GEDCOM data can be accessed via `GedcomLoader`:

```php
<?php
$loader = new Aazsamir\Pamily\Import\Gedcom\GedcomLoader();
// raw data as array of lines
$data = $loader->load('path/to/your/file.ged');
// nested tree structure
$treeData = $loader->loadTree('path/to/your/file.ged');
```

## Current Status

Early development stage, just working on it for my personal needs.

## License

Pamily is open-source software licensed under the MIT License. See the [LICENSE](LICENSE) file for more details.