<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

if (isset($argv[2])) {
    $disk_rows = $argv[2];
} else {
    $disk_rows = '128';
}

require '../classes/KnotHash.class.php';
require '../classes/Defragmentor.class.php';

$defragmentor = new Defragmentor($disk_rows, $input);
// Part 1
echo ('used sectors: ' . $defragmentor->getUsedSectorCount() . "\n");

// Part 2
echo ('region count: ' . $defragmentor->getRegionCount() . "\n");
