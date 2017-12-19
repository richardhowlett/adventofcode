<?php

if (isset($argv[1])) {
    $number_of_dancers = $argv[1];
} else {
    $number_of_dancers = 16;
}

if (isset($argv[2])) {
    $input = $argv[2];
} else {
    $input = 's2';
}

require 'Dance.class.php';

for ($i = 0; $i < $number_of_dancers; $i++) {
    $dancers[$i] = chr($i + 97);
}

$dance = new Dance($dancers);

$dance_moves = explode(',', $input);

$dance->dance($dance_moves);
echo ('result: ' . $dance->getDancePositions() . "\n");

// TODO Refactor Dance to use string manipulations rather than array (if quicker)
// update this logic to detect a return to start position (if one occurs) in order to potentially reduce number of iterations required to calculate answer
for ($i = 1; $i < 1000000000; $i++) {
    $dance->dance($dance_moves);

    if ($i % 100000 == 0) {
        echo ('result after iteration: ' . $i . ' - ' . $dance->getDancePositions() . "\n");
    }
}

echo ('result: ' . $dance->getDancePositions() . "\n");
