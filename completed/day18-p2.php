<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

require '../classes/DuetRegister.class.php';
require '../classes/Duet2.class.php';
require '../classes/DuetCoordinator.class.php';

$duet_coordinator = new DuetCoordinator($input);
$duet_coordinator->run();

//echo ('result: ' . $lock->lock_values[$lock->current_position + 1] . "\n");
