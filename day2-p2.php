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

    //$min = min($columns);
    //$max = max($columns);

    //$sum += ($max - $min);

    foreach ($columns as $loop_1_index => $column_a) {
        foreach ($columns as $loop_2_index => $column_b) {
            if ($loop_1_index != $loop_2_index && $column_a % $column_b == 0) {
                $sum += ($column_a / $column_b);
            }
        }
    }
}

echo ('result: ' . $sum);
