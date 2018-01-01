<?php

class NetworkMap
{
    public $map;
    public $x = 0;
    public $y = 0;
    public $movement_x = 0;
    public $movement_y = 1;
    public $visited_nodes = array();

    public function __construct($input)
    {
        $map_lines = explode("\n", $input);
        foreach ($map_lines as $map_line) {
            $this->map[] = str_split($map_line);
        }

        $this->setStartCoordinates();
    }

    public function printMap()
    {
        foreach ($this->map as $map_row) {
            echo (implode('', $map_row) . "\n");
        }
    }

    public function setStartCoordinates()
    {
        foreach ($this->map[$this->y] as $index => $value) {
            if (' ' !== $value) {
                $this->x = $index;
                break;
            }
        }
    }

    public function processValue($value)
    {
        switch ($value) {
            case '+':
                if ($this->movement_x !== 0) {
                    $this->movement_x = 0;

                    // find new y vector
                    if (!empty($this->map[$this->y + 1][$this->x]) && ' ' != $this->map[$this->y + 1][$this->x]) {
                        $this->movement_y = 1;
                    } elseif (!empty($this->map[$this->y - 1][$this->x]) && ' ' != $this->map[$this->y - 1][$this->x]) {
                        $this->movement_y = -1;
                    } else {
                        // walk complete
                        $this->movement_y = 0;
                    }
                } else {
                    $this->movement_y = 0;

                    // find new x vector
                    if (!empty($this->map[$this->y][$this->x + 1]) && ' ' != $this->map[$this->y][$this->x + 1]) {
                        $this->movement_x = 1;
                    } elseif (!empty($this->map[$this->y][$this->x - 1]) && ' ' != $this->map[$this->y][$this->x - 1]) {
                        $this->movement_x = -1;
                    } else {
                        // walk complete
                        $this->movement_x = 0;
                    }
                }
                break;

            case '-':
                break;

            case '|':
                break;

            case ' ':
                $this->movement_x = 0;
                $this->movement_y = 0;
                break;

            default:
                $this->visited_nodes[] = $value;
                break;
        }

        if (-1 == $this->movement_y) {
            // echo ('up' . PHP_EOL);
        }
        if (1 == $this->movement_y) {
            // echo ('down' . PHP_EOL);
        }
        if (-1 == $this->movement_x) {
            // echo ('left' . PHP_EOL);
        }
        if (1 == $this->movement_x) {
            // echo ('right' . PHP_EOL);
        }

        $this->y += $this->movement_y;
        $this->x += $this->movement_x;
    }

    public function run()
    {
        echo ('start_x: ' . $this->x . PHP_EOL);
        echo ('start_y: ' . $this->y . PHP_EOL);
        echo ('movement_x: ' . $this->movement_x . PHP_EOL);
        echo ('movement_y: ' . $this->movement_y . PHP_EOL);

        $this->step = -1;
        while (0 != $this->movement_x || 0 != $this->movement_y) {
            $this->step++;

            // echo ('step: ' . $step . PHP_EOL);

            $current_value = $this->map[$this->y][$this->x];
            // echo ('current value: ' . $current_value . PHP_EOL);

            $this->processValue($current_value);
        }

        // echo ('done' . PHP_EOL);
    }
}
