<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

if (isset($argv[2])) {
    $insertions = $argv[2];
} else {
    $insertions = '';
}

require '../classes/SpinLock.class.php';

$lock = new SpinLock($input, 1);

for ($i = 0; $i < $insertions; $i++) {
    $lock->spin();

    if ($i % 1000 === 0) {
        echo ('iteration: ' . $i . "\n");
    }
}

echo ('result: ' . $lock->lock_values[$lock->current_position + 1] . "\n");
