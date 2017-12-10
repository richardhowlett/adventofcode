<?php

if (isset($argv[1])) {
    $source_string_length = $argv[1];
} else {
    $source_string_length = '';
}

if (isset($argv[2])) {
    $input = $argv[2];
} else {
    $input = '';
}

$source_string = array();

for ($i = 0; $i < $source_string_length; $i++) {
    $source_string[] = $i;
}

echo ('source_string: ' . implode(' ', $source_string) . "\n");

$input_lengths = str_split($input);
foreach ($input_lengths as &$input_length) {
    $input_length = ord($input_length);
}

$input_lengths = array_merge($input_lengths, array(17, 31, 73, 47, 23));

$current_position = 0;
$skip_size = 0;
for ($i = 0; $i < 64; $i++) {
    foreach ($input_lengths as $input_length) {
        //echo ('source_string: ' . $source_string . "\n");
        //echo ('current_position: ' . $current_position . "\n");
        $tmp_string = array_merge($source_string, $source_string);
        $string_to_reverse = array_splice($tmp_string, $current_position, $input_length);
        $reversed_string = array_reverse($string_to_reverse);

        echo ('input_length: ' . $input_length . "\n");
        echo ('string_to_reverse: ' . implode(' ', $string_to_reverse) . "\n");
        echo ('reversed_string: ' . implode(' ', $reversed_string) . "\n");

        $update_position = $current_position;
        for ($j = 0; $j < $input_length; $j++) {
            if ($update_position >= $source_string_length) {
                $update_position = 0;
            }

            //echo ('update position: ' . $update_position . "\n");

            $source_string[$update_position] = $reversed_string[$j];

            $update_position++;
        }

        $current_position += $input_length + $skip_size;
        if ($current_position >= $source_string_length) {
            $current_position = $current_position - $source_string_length;
        }
        $skip_size++;
    }
}

echo ('new source string: ' . implode(' ', $source_string) . "\n");
echo ('result: ' . ($source_string[0] * $source_string[1]) . "\n");
