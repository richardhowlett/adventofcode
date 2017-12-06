<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '11	11	13	7	0	15	5	5	4	4	1	1	7	1	15	11';
}

//$input = '0	2	7	0';

$past_banks = array();
$bank = explode("\t", $input);

$bank_slot_count = count($bank);

//print_r($past_banks);
//print_r($bank);

$loops = 0;
$occurances_required = 2;

function bank_filter($bank_to_check) {
    global $bank;

    return (implode('', $bank) === $bank_to_check);
}

while (true) {
    $occurances = array_filter($past_banks, "bank_filter");

    if (count($occurances) >= $occurances_required) {
        //print_r($occurances);
        $occurance_keys = array_keys($occurances);
        //print_r($occurance_keys);
        $last = array_pop($occurance_keys);
        $first = array_shift($occurance_keys);
        //echo ('last: ' . $last . "\n");
        //echo ('first: ' . $first . "\n");
        die ('result: ' . ($last - $first));
    }

    // store the current bank into history
    $past_banks[] = implode('', $bank);

    // find the bank slot with highest value
    $max_bank_slot = array_search(max($bank), $bank);
    //echo ('max_bank_slot: ' . $max_bank_slot . "\n");

    $max_bank_slot_value = $bank[$max_bank_slot];

    // set the bank slot with highest value to 0
    $bank[$max_bank_slot] = 0;

    $active_bank_slot = $max_bank_slot + 1;

    // re-distribute the bank's data
    for ($i = 0; $i < $max_bank_slot_value; $i++) {
        if ($active_bank_slot >= $bank_slot_count) {
            $active_bank_slot = 0;
        }

        $bank[$active_bank_slot]++;

        $active_bank_slot++;
    }

    //print_r($bank);

    $loops++;
}

echo ('loops: ' . $loops);
