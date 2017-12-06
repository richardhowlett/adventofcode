<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}
/*
function addIfDupe($sum, $input)
{
    $input_string = $input . $input;
    $check_digit = strlen($input / 2);


    if ($input[0] == $input[1]) {
        $sum += $input[0];
    }

    if (strlen($input) > 2) {
        return addIfDupe($sum, substr($input, 1));
    } else {
        return $sum;
    }
}

$result = addIfDupe(0, $input);
*/

$check_string = $input . $input;
$check_ahead_count = strlen($input) / 2;

echo ('check_string: ' . $check_string . "\n");
echo ('check_ahead_count: ' . $check_ahead_count . "\n");

$sum = 0;
for ($i = 0; $i < strlen($input); $i++) {
    $current = $check_string[$i];
    $check_ahead_item = $check_string[$i + $check_ahead_count];

    echo ('current: ' . $current . "\n");
    echo ('check_ahead_item: ' . $check_ahead_item . "\n");

    if ($current == $check_ahead_item) {
        $sum += $current;
    }
}

echo ('sum: ' . $sum);
