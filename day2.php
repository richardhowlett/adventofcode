<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

$rows = explode("\n", $input);

//print_r($rows);

$sum = 0;
foreach ($rows as $row) {
    $columns = explode("\t", $row);

    //print_r($columns);

    $min = min($columns);
    $max = max($columns);

    $sum += ($max - $min);
}

echo ('result: ' . $sum);
