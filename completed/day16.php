<?php

if (isset($argv[1])) {
    $number_of_dancers = $argv[1];
} else {
    $number_of_dancers = 16;
}

if (isset($argv[2])) {
    $max_iterations = $argv[2];
} else {
    $max_iterations = 1;
}

if (isset($argv[3])) {
    $input = $argv[3];
} else {
    $input = '';
}

require 'classes/Dance.class.php';

for ($i = 0; $i < $number_of_dancers; $i++) {
    $dancers[$i] = chr($i + 97);
}

$dance = new Dance($dancers);

$dance_moves = explode(',', $input);
$dance->logDancePositions();
$dance->dance($dance_moves);
$dance->logDancePositions();

// TODO Refactor Dance to use string manipulations rather than array (if quicker)
for ($i = 1; $i < $max_iterations; $i++) {
    $dance->dance($dance_moves);

    $found_index = $dance->danceLoopDetected();
    $dance->logDancePositions();

    if (null !== $found_index) {
        //echo ('loop found, last occurance of "' . $dance->getDancePositions() . '" occured iteration "' . $found_index . '"' . "\n");

        $gap = $dance->dance_count - $found_index - 1;
        //echo ('gap: ' . $gap . "\n");

        $remaining_iterations = $max_iterations - $i;
        //echo ('remaining_iterations: ' . $remaining_iterations . "\n");

        $repeat_loop_times = floor($remaining_iterations / $gap);
        //echo ('completable loops: ' . $repeat_loop_times . "\n");

        if ($remaining_iterations > 0) {
            //echo ('current i: ' . $i . "\n");
            $i += ($repeat_loop_times * $gap);
            //echo ('new i: ' . $i . "\n");
        }
    }

    if ($i % 100000 == 0) {
        echo ('result after iteration: ' . $i . ' - ' . $dance->getDancePositions() . "\n");
    }
}

//print_r($dance);

echo ('result: ' . $dance->getDancePositions() . "\n");
