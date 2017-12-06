<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

$value_array = explode("\n", $input);
$pointer = 0;

$steps = 0;
while ($pointer >= 0 && $pointer < count($value_array)) {
    //print_r($value_array);

    //echo ('pointer: ' . $pointer . "\n");

    // get the current value
    $value = $value_array[$pointer];

    // increment the current value
    $value_array[$pointer]++;

    //echo ('value: ' . $value . "\n");

    // set the new pointer location
    $pointer += $value;

    //echo ('pointer: ' . $pointer . "\n");

    $steps++;
}

echo ('steps: ' . $steps);
