<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '<{o"i!a,<{i<a>';
}

$is_garbage = false;
$group_depth = 0;
$group_count = 0;
$score = 0;
$garbage_count = 0;

while (strlen($input) > 0) {
    //echo ('input: ' . $input . "\n");
    $char = substr($input, 0, 1);

    if ($is_garbage) {
        //echo ('add garbage');
        $garbage_count++;
    }

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
            $garbage_count--;
            break;

        case '!':
            $input = substr($input, 1);
            $garbage_count--;
            break;
    }

    $input = substr($input, 1);
}

echo ('result: ' . $group_count . "\n");
echo ('score: ' . $score . "\n");
echo ('garbage_count: ' . $garbage_count . "\n");
