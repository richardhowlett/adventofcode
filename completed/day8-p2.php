<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

$instructions = explode("\n", $input);
$max_value = 0;

function performOperation(&$a, $operator, $b) {
    switch ($operator) {
        case 'inc':
            $a += $b;
            break;

        case 'dec':
            $a -= $b;
            break;

        default:
            throw new Exception('Unknown operator "' . $operator . '"');
            break;
    }
}

function performComparison($a, $operator, $b) {
    switch ($operator) {
        case '>':
            return $a > $b;
            break;

        case '<':
            return $a < $b;
            break;

        case '>=':
            return $a >= $b;
            break;

        case '<=':
            return $a <= $b;
            break;

        case '==':
            return $a == $b;
            break;

        case '!=':
            return $a != $b;
            break;

        default:
            throw new Exception('Unknown operator "' . $operator . '"');
            break;
    }
}

$register = array();
foreach ($instructions as $instruction) {
    $matches = array();
    preg_match('/(.+) (inc|dec) ([-0-9]+) if ([^ ]*) ([^ ]*) (.*)/', $instruction, $matches);

    //print_r($matches);

    $register_to_modify = $matches[1];
    $operation = $matches[2];
    $amount = $matches[3];

    $register_to_check = $matches[4];
    $comparison_operator = $matches[5];
    $comparison_value = $matches[6];

    if (!isset($register[$register_to_check])) {
        $register[$register_to_check] = 0;
    }
    $check_value = $register[$register_to_check];

    // if comparison value has a non numeric character, assume it is a register pointer instead.
    if (preg_match('/[^-0-9]/', $comparison_value)) {
        if (!isset($register[$comparison_value])) {
            $register[$comparison_value] = 0;
        }
        $comparison_value = $register[$comparison_value];
    }

    if (performComparison($check_value, $comparison_operator, $comparison_value)) {
        if (!isset($register[$register_to_modify])) {
            $register[$register_to_modify] = 0;
        }
        $modify_value = &$register[$register_to_modify];

        //echo ('run operation');
        performOperation($modify_value, $operation, $amount);

        if ($modify_value > $max_value) {
            $max_value = $modify_value;
        }
    }

    //print_r($register);
}

//print_r($register);

echo ('max register value: ' . max($register) . "\n");
echo ('max value: ' . $max_value);