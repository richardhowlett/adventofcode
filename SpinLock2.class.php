<?php

class SpinLock2
{
    public $lock_move_size;
    public $lock_value_increment;
    public $lock_values = array(0);
    public $lock_length = 1;
    public $current_position = 0;
    public $current_value = 0;
    public $index_1_value = null;

    public function __construct($lock_move_size, $lock_value_increment = 1)
    {
        $this->lock_move_size = $lock_move_size;
        $this->lock_value_increment = $lock_value_increment;
    }

    public function movePosition()
    {
        $new_position = $this->current_position + $this->lock_move_size;

        if ($new_position >= $this->lock_length) {
            $new_position = $new_position % $this->lock_length;
        }

        $this->current_position = $new_position;
    }

    public function writeNextPosition()
    {
        $this->current_position++;
        $this->current_value += $this->lock_value_increment;

        if ($this->current_position == 1) {
            echo ('writing to index 1: ' . $this->current_value . "\n");
            $this->index_1_value = $this->current_value;
        }

        $this->lock_length++;
    }

    public function spin()
    {
        $this->movePosition();
        $this->writeNextPosition();
    }
}
