<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

$is_garbage = false;
$group_depth = 0;
$group_count = 0;
$score = 0;

while (strlen($input) > 0) {
    $char = substr($input, 0, 1);

    switch ($char) {
        case '{':
            if (!$is_garbage) {
                $group_depth++;
            }
            break;

        case '}':
            if (!$is_garbage) {
                if ($group_depth > 0) {
                    $group_count++;
                    $score += $group_depth;
                }
                $group_depth--;
            }
            break;

        case '<':
            $is_garbage = true;
            break;

        case '>':
            $is_garbage = false;
            break;

        case '!':
            $input = substr($input, 1);
    }

    $input = substr($input, 1);
}

echo ('result: ' . $group_count . "\n");
echo ('score: ' . $score);
