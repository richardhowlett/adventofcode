<?php

ini_set('memory_limit', '1G');

class Map
{
    public $map = array();
    public $x = 0;
    public $y = 0;
    public $input;
    public $value = 0;

    public function __construct($input, $debug = false)
    {
        $this->input = $input;
        $this->debug = $debug;
        $this->generateMap();
    }

    function generateMap()
    {
        $this->map = array();
        $rings = ceil((sqrt($this->input) - 1) / 2);

        for ($i = -$rings; $i <= $rings; $i++) {
            for ($j = -$rings; $j <= $rings; $j++) {
                $this->map[$i][$j] = null;
            }
        }

        //$this->map[0][0] = 1;
    }

    function renderMap()
    {
        // Currently prints 90 rotated..
        $tmp_array = array();
        foreach ($this->map as $row_index => $row_data) {
            $row_data = array_reverse($row_data);
            foreach ($row_data as $col_index => $col_data) {
                $tmp_array[$col_index][$row_index] = $col_data;
            }
        }

        //print_r($this->map);
        foreach ($tmp_array as $row_data) {
            foreach ($row_data as $col_data) {
                echo (str_pad($col_data ? $col_data : '-', 4, ' '));
                echo (' ');
            }

            echo ("\n");
        }
    }

    function setMapValue()
    {
        $populated = $this->checkPopulated();
        $sum = array_sum($populated);
        if ($sum == 0) {
            $sum = 1;
        }
        if ($this->debug) {
            print_r($populated);
            echo ('sum to set: ' . $sum . "\n");
        }
        $this->value = $sum;
        $this->map[$this->x][$this->y] = $this->value;
    }

    function moveUp()
    {
        if ($this->debug) {
            echo ("Up\n");
        }

        $this->y++;
    }

    function moveDown()
    {
        if ($this->debug) {
            echo ("Down\n");
        }

        $this->y--;
    }

    function moveLeft()
    {
        if ($this->debug) {
            echo ("Left\n");
        }

        $this->x--;
    }

    function moveRight()
    {
        if ($this->debug) {
            echo ("Right\n");
        }

        $this->x++;
    }

    function checkPopulated()
    {
        $populated = array(
            'left' => null,
            'up-left' => null,
            'up' => null,
            'up-right' => null,
            'right' => null,
            'down-right' => null,
            'down' => null,
            'down-left' => null,
        );
        if (isset($this->map[$this->x - 1][$this->y])) {
            $populated['left'] = $this->map[$this->x - 1][$this->y];
        }
        if (isset($this->map[$this->x - 1][$this->y + 1])) {
            $populated['up-left'] = $this->map[$this->x - 1][$this->y + 1];
        }
        if (isset($this->map[$this->x][$this->y + 1])) {
            $populated['up'] = $this->map[$this->x][$this->y + 1];
        }
        if (isset($this->map[$this->x + 1][$this->y + 1])) {
            $populated['up-right'] = $this->map[$this->x + 1][$this->y + 1];
        }
        if (isset($this->map[$this->x + 1][$this->y])) {
            $populated['right'] = $this->map[$this->x + 1][$this->y];
        }
        if (isset($this->map[$this->x + 1][$this->y - 1])) {
            $populated['down-right'] = $this->map[$this->x + 1][$this->y - 1];
        }
        if (isset($this->map[$this->x][$this->y - 1])) {
            $populated['down'] = $this->map[$this->x][$this->y - 1];
        }
        if (isset($this->map[$this->x - 1][$this->y - 1])) {
            $populated['down-left'] = $this->map[$this->x - 1][$this->y - 1];
        }

        return $populated;
    }

    function walk()
    {
        if ($this->debug) {
            echo ("Walk \n");
        }

        $this->setMapValue();

        if ($this->value > $this->input) {
            //return abs($this->x) + abs($this->y);
            return $this->value;
        }

        if ($this->debug) {
            echo ('x: ' . $this->x . "\n");
            echo ('y: ' . $this->y . "\n");
            $this->renderMap();
        }

        $populated = $this->checkPopulated();

        if ($populated['left'] !== null && $populated['up'] === null) {
            // move Up (left populated, up not)
            $this->moveUp();
        } elseif ($populated['right'] !== null && $populated['down'] === null) {
            // move Down (right populated, down not)
            $this->moveDown();
        } elseif ($populated['left'] === null && $populated['down'] !== null) {
            // Move Left (left not populated, down populated)
            $this->moveLeft();
        } else {
            // Move Right (up populated, right not)
            $this->moveRight();
        }

        return $this->walk();
    }
}

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = 9;
}

$map = new Map($input, false);
$result = $map->walk();
//$map->renderMap();

//echo ('x: ' . $map->x . "\n");
//echo ('y: ' . $map->y . "\n");

echo ('result: ' . $result);
