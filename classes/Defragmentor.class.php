<?php

class Defragmentor
{
    public $disk_cols;
    public $disk_rows;
    public $disk_sectors;

    public function __construct($hash_count, $input)
    {
        $this->disk_cols = $hash_count;
        $this->disk_rows = $hash_count;
        $this->initialiseDisk($hash_count, $input);
    }

    public function initialiseDisk($hash_count, $input)
    {
        $this->disk_sectors = array();
        for ($i = 0; $i < $hash_count; $i++) {
            $result_string = KnotHash::hash($input . '-' . $i);
            if (strlen($result_string) !== 32) {
                echo ('input: ' . $input . '-' . $i . "\n");
                die('hash incorrect length: ' . $result_string . ' - ' . strlen($result_string));
            }
            $binary_hash = KnotHash::hex2bin($result_string);

            if (strlen($binary_hash) !== 128) {
                echo ('input: ' . $input . '-' . $i . "\n");
                die('binary hash incorrect length: ' . $binary_hash);
            }

            foreach (str_split($binary_hash) as $disk_column_index => $character) {
                $this->disk_sectors[$i][$disk_column_index] = array(
                    'used' => ($character) ? true : false,
                    'region' => null,
                );
            }
        }
    }

    function getUsedSectorCount() {
        $count = 0;
        foreach ($this->disk_sectors as $disk_row) {
            foreach ($disk_row as $sector) {
                if ($sector['used']) {
                    $count++;
                }
            }
        }

        return $count;
    }

    public function isSectorInUse($row, $column) {
        return $this->disk_sectors[$row][$column]['used'];
    }

    public function isSectorInRegion($row, $column) {
        return $this->disk_sectors[$row][$column]['region'] !== null;
    }

    public function updateConnectedSectors($row, $column, $region) {
        if ($this->isSectorInUse($row, $column) && !$this->isSectorInRegion($row, $column)) {
            $this->disk_sectors[$row][$column]['region'] = $region;

            // if there is a sector above
            if (($row - 1) >= 0) {
                $this->updateConnectedSectors($row - 1, $column, $region);
            }

            // if there is a sector below
            if (($row + 1) < $this->disk_rows) {
                $this->updateConnectedSectors($row + 1, $column, $region);
            }

            // if there is a sector left
            if (($column - 1) >= 0) {
                $this->updateConnectedSectors($row, $column - 1, $region);
            }

            // if there is a sector right
            if (($column + 1) < $this->disk_cols) {
                $this->updateConnectedSectors($row, $column + 1, $region);
            }
        }
    }

    public function getRegionCount() {
        $this->current_region = 0;

        for ($row = 0; $row < $this->disk_rows; $row++) {
            for ($col = 0; $col < $this->disk_cols; $col++) {
                if (!$this->isSectorInRegion($row, $col) && $this->isSectorInUse($row, $col)) {
                    $this->current_region++;
                    $this->updateConnectedSectors($row, $col, $this->current_region);
                }
            }
        }

        return $this->current_region;
    }

    public function printDisk($mode = 'used', $from_row = 0, $for_rows = null, $from_col = 0, $for_cols = null) {
        if ($for_rows == null) {
            $for_rows = $this->disk_rows - 1 - $from_row;
        }
        if ($for_cols == null) {
            $for_cols = $this->disk_cols - 1 - $from_col;
        }
        $output = '';
        for ($row = $from_row; $row <= $for_rows; $row++) {
            for ($col = $from_col; $col <= $for_cols; $col++) {
                if ($this->disk_sectors[$row][$col]['used']) {
                    if ($mode == 'used') {
                        $output .= '#';
                    } else {
                        $output .= $this->disk_sectors[$row][$col]['region'];
                    }
                } else {
                    $output .= '.';
                }
            }
            $output .= "\n";
        }

        echo $output;
    }
}
