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
    }

    function renderMap()
    {
        // Currently prints 90 rotated..

        //print_r($this->map);
        foreach ($this->map as $row_data) {
            foreach ($row_data as $col_data) {
                echo ($col_data ? $col_data : '-');
                echo (' ');
            }

            echo ("\n");
        }
    }

    function setMapValue()
    {
        $this->value++;
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
            'left' => false,
            'up' => false,
            'down' => false,
            'right' => false,
        );
        if (isset($this->map[$this->x - 1][$this->y]) && $this->map[$this->x - 1][$this->y] !== null) {
            $populated['left'] = true;
        }
        if (isset($this->map[$this->x][$this->y + 1]) && $this->map[$this->x][$this->y + 1] !== null) {
            $populated['up'] = true;
        }
        if (isset($this->map[$this->x][$this->y - 1]) && $this->map[$this->x][$this->y - 1] !== null) {
            $populated['down'] = true;
        }
        if (isset($this->map[$this->x + 1][$this->y]) && $this->map[$this->x + 1][$this->y] !== null) {
            $populated['right'] = true;
        }

        return $populated;
    }

    function walk()
    {
        if ($this->debug) {
            echo ("Walk \n");
        }

        $this->setMapValue();

        if ($this->value == $this->input) {
            return abs($this->x) + abs($this->y);
        }

        //echo ('x: ' . $this->x . "\n");
        //echo ('y: ' . $this->y . "\n");
        //$this->renderMap();

        $populated = $this->checkPopulated();

        if ($populated['left'] && !$populated['up']) {
            // move Up (left populated, up not)
            $this->moveUp();
        } elseif ($populated['right'] && !$populated['down']) {
            // move Down (right populated, down not)
            $this->moveDown();
        } elseif (!$populated['left'] && $populated['down']) {
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

$map = new Map($input);
$result = $map->walk();
//$map->renderMap();

//echo ('x: ' . $map->x . "\n");
//echo ('y: ' . $map->y . "\n");

echo ('result: ' . $result);
