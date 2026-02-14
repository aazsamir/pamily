#!/usr/bin/env php
<?php

use Aazsamir\Pamily\Import\GenericParser;

include_once __DIR__ . '/vendor/autoload.php';

$filename = $_SERVER['argv'][1] ?? null;

$parser = new GenericParser();
$data = $parser->parse($filename);