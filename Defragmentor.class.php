<?php

class Defragmentor
{
    public $disk_hashes;
    public $disk_sectors;

    public function __construct($hash_count, $input)
    {
        $this->disk_hashes = $this->initialiseDisk($hash_count, $input);
    }

    public function initialiseDisk($hash_count, $input)
    {
        $result_grid = array();
        for ($i = 0; $i < $hash_count; $i++) {
            $result_string = KnotHash::hash($input . '-' . $i);
            $binary = KnotHash::hex2bin($result_string);

            foreach (str_split($binary) as $disk_column_index => $character) {
                $this->disk_sectors[$i][$disk_column_index] = array(
                    'used' => ($character) ? 1 : 0,
                    'region' => null,
                );
            }

            $results[] = $binary;
        }

        return $results;
    }

    function getUsedSectorCount() {
        $count = 0;
        foreach ($this->disk_hashes as $disk_row) {
            foreach (str_split($disk_row) as $character) {
                if ($character) {
                    $count++;
                }
            }
        }

        return $count;
    }

    function getDiskSectors() {
        $disk_sectors = array();
        foreach ($this->disk_hashes as $disk_row_index => $disk_row) {
            $disk_sectors[$disk_row_index] = array();
            foreach (str_split($disk_row) as $disk_column_index => $character) {
                $disk_sectors[$disk_row_index][$disk_column_index] = array(
                    'used' => ($character) ? 1 : 0,
                    'region' => null,
                );
            }
        }

        return $disk_sectors;
    }

    public function getRegionCount() {
        $disk_sectors = $this->getDiskSectors();
        print_r(array_diff($disk_sectors, $this->disk_sectors));
        die('test');

        /*$current_region = 0;

        foreach ($disk_sectors as $disk_row_index => &$disk_row) {
            foreach ($disk_row as $disk_column_index => &$sector) {
                if ($sector['region'] == null)
            }
        }*/
    }
}