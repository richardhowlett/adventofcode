<?php

if (isset($argv[1])) {
    $source_string_length = $argv[1];
} else {
    $source_string_length = '256';
}

if (isset($argv[2])) {
    $input = $argv[2];
} else {
    //$input = 'flqrgnkx-0';
    $input = '';
}

require 'KnotHash.class.php';
require 'Defragmentor.class.php';

$defragmentor = new Defragmentor(128, $input);
echo ('used sectors: ' . $defragmentor->getUsedSectorCount() . "\n");

//$defragmentor->getRegionCount();