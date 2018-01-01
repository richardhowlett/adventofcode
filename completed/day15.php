<?php

if (isset($argv[1])) {
    $rounds = $argv[1];
} else {
    $rounds = 5;
}

if (isset($argv[2])) {
    $input = $argv[2];
} else {
    $input = '';
}

require '../classes/NumberGenerator.class.php';
require '../classes/Judge.class.php';

/*
$generator_values = array(
    'Generator A' => array(
        'initial_value' => null,
        'factor' => 16807,
        'denominator' => 2147483647,
    ),
    'Generator B' => array(
        'initial_value' => null,
        'factor' => 48271,
        'denominator' => 2147483647,
    ),
);

foreach (explode("\n", $input) as $generator_start_data) {
    $parts = explode(' starts with ', $generator_start_data);
    $generator_values[$parts[0]]['initial_value'] = $parts[1];
}

$generators = array();
foreach ($generator_values as $generator_name => $generator_data) {
    $generators[$generator_name] = new NumberGenerator(
        $generator_data['initial_value'],
        $generator_data['factor'],
        $generator_data['denominator']
    );
}

// Part 1
$judge = new Judge($generators);
$match_count = $judge->getMatchCount($rounds);
*/

$generator_values = array(
    'Generator A' => array(
        'initial_value' => null,
        'factor' => 16807,
        'denominator' => 2147483647,
        'validation_denominator' => 4,
    ),
    'Generator B' => array(
        'initial_value' => null,
        'factor' => 48271,
        'denominator' => 2147483647,
        'validation_denominator' => 8,
    ),
);

foreach (explode("\n", $input) as $generator_start_data) {
    $parts = explode(' starts with ', $generator_start_data);
    $generator_values[$parts[0]]['initial_value'] = $parts[1];
}

$generators = array();
foreach ($generator_values as $generator_name => $generator_data) {
    $generators[$generator_name] = new NumberGenerator(
        $generator_data['initial_value'],
        $generator_data['factor'],
        $generator_data['denominator'],
        $generator_data['validation_denominator']
    );
}

// Part 2
$judge = new Judge($generators);
$match_count = $judge->getMatchCount($rounds);
