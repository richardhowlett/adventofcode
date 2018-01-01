<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

$past_banks = array();
$bank = explode("\t", $input);

$bank_slot_count = count($bank);

//print_r($past_banks);
//print_r($bank);

$loops = 0;
while (!in_array(implode('', $bank), $past_banks)) {
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
