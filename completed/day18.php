<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

require '../classes/Duet.class.php';

$duet = new Duet($input);
$duet->run();

echo ('result: ' . $lock->lock_values[$lock->current_position + 1] . "\n");
