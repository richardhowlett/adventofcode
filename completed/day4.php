<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

$phrases = explode("\n", $input);

$valid_count = 0;
foreach ($phrases as $phrase) {
    if (strtolower($phrase) == $phrase) {
        $words = explode(' ', $phrase);

        $unique = array_unique($words);

        if (count($words) == count($unique)) {
            $valid_count++;
        }
    }
}

echo ('result: ' . $valid_count);
