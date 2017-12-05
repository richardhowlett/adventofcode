<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

function addIfDupe($sum, $input)
{
    if ($input[0] == $input[1]) {
        $sum += $input[0];
    }

    if (strlen($input) > 2) {
        return addIfDupe($sum, substr($input, 1));
    } else {
        return $sum;
    }
}

$result = addIfDupe(0, $input . $input[0]);

echo ('result: ' . $result);
