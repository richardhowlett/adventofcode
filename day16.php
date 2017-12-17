<?php

if (isset($argv[1])) {
    $number_of_dancers = $argv[1];
} else {
    $number_of_dancers = 5;
}

if (isset($argv[2])) {
    $input = $argv[2];
} else {
    $input = '';
}

require 'Dance.class.php';

for ($i = 0; $i < $number_of_dancers; $i++) {
    $dancers[$i] = chr($i + 97);
}

$dance = new Dance($dancers);
$result = $dance->dance($input);
echo ('result: ' . $result);
