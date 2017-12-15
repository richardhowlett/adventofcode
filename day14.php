<?php

if (isset($argv[1])) {
    $source_string_length = $argv[1];
} else {
    $source_string_length = '256';
}

if (isset($argv[2])) {
    $input = $argv[2];
} else {
    //$input = 'flqrgnkx-0';
    $input = '';
}

class KnotHash
{
    static function convertInput($input)
    {
        if ($input != '') {
            $input_lengths = str_split($input);
            foreach ($input_lengths as &$input_length) {
                $input_length = ord($input_length);
            }
        } else {
            $input_lengths = array();
        }

        $input_lengths = array_merge($input_lengths, array(17, 31, 73, 47, 23));

        return $input_lengths;
    }

    static function hash($source_string_length, $input)
    {
        $source_string = array();
        for ($i = 0; $i < $source_string_length; $i++) {
            $source_string[] = $i;
        }
        //echo ('source_string: ' . implode(' ', $source_string) . "\n");

        $input_lengths = self::convertInput($input);

        $current_position = 0;
        $skip_size = 0;
        for ($i = 0; $i < 64; $i++) {
            foreach ($input_lengths as $input_length) {
                //echo ('source_string: ' . $source_string . "\n");
                //echo ('current_position: ' . $current_position . "\n");
                $tmp_string = array_merge($source_string, $source_string);
                $string_to_reverse = array_splice($tmp_string, $current_position, $input_length);
                $reversed_string = array_reverse($string_to_reverse);

                //echo ('input_length: ' . $input_length . "\n");
                //echo ('string_to_reverse: ' . implode(' ', $string_to_reverse) . "\n");
                //echo ('reversed_string: ' . implode(' ', $reversed_string) . "\n");

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
                while ($current_position >= $source_string_length) {
                    $current_position = $current_position - $source_string_length;
                }
                $skip_size++;
            }
        }

        return self::convertToHex($source_string);
    }

    static function convertToHex($source_string)
    {
        $chunks = array_chunk($source_string, 16);

        $result_string = '';
        foreach ($chunks as $chunk) {
            $result = array_shift($chunk);

            foreach ($chunk as $c) {
                $result = $result ^ $c;
            }

            $result_string .= dechex($result);
        }

        return $result_string;
    }

    static function hex2bin($source_string) {
        $result_string = '';

        foreach (str_split($source_string) as $character) {
            //echo ('charactersrc: ' . $character . "\n");
            //echo ('characterhex: ' . hexdec($character) . "\n");
            //echo ('characterbin: ' . decbin(hexdec($character)) . "\n");

            $converted = str_pad(decbin(hexdec($character)), 4, 0, STR_PAD_LEFT);

            //echo ('converted: ' . $converted . "\n");
            $result_string .= $converted;
        }

        return $result_string;
    }

    static function bin2print($source_string) {
        $result_string = '';

        foreach (str_split($source_string) as $character) {
            if ($character) {
                $result_string .= '#';
            } else {
                $result_string .= '.';
            }
        }

        return $result_string;
    }
}

for ($i = 0; $i <= 8; $i++) {
    $result_string = KnotHash::hash($source_string_length, $input . '-' . $i);
    $binary = KnotHash::hex2bin($result_string);
    $print = KnotHash::bin2print($binary);

    echo ('result_string: ' . $result_string . "\n");
    echo ('binary: ' . substr($binary, 0, 8) . "\n");
    echo ('print: ' . substr($print, 0, 8) . "\n");
}
