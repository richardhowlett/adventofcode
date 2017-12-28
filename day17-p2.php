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

require 'SpinLock2.class.php';

$lock = new SpinLock2($input, 1);

for ($i = 0; $i < $insertions; $i++) {
    $lock->spin();

    if ($i % 100000 === 0) {
        echo ('iteration: ' . $i . "\n");
    }
}

echo ('index 1 value: ' . $lock->index_1_value . "\n");
