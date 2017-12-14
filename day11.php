<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

class Map
{
    public $x = 0;
    public $y = 0;
    public $input;
    public $steps;
    public $distance = 0;
    public $max_distance = 0;

    public function __construct($input)
    {
        $this->input = $input;
        $this->steps = explode(',', $this->input);
    }

    function moveN()
    {
        $this->y += 1;
    }

    function moveNE()
    {
        $this->x += 0.5;
        $this->y += 0.5;
    }

    function moveSE()
    {
        $this->x += 0.5;
        $this->y -= 0.5;
    }

    function moveS()
    {
        $this->y -= 1;
    }

    function moveSW()
    {
        $this->x -= 0.5;
        $this->y -= 0.5;
    }

    function moveNW()
    {
        $this->x -= 0.5;
        $this->y += 0.5;
    }

    function walk()
    {
        foreach ($this->steps as $step) {
            switch ($step) {
                case 'n':
                    $this->moveN();
                    break;

                case 'ne':
                    $this->moveNE();
                    break;

                case 'se':
                    $this->moveSE();
                    break;

                case 's':
                    $this->moveS();
                    break;

                case 'sw':
                    $this->moveSW();
                    break;

                case 'nw':
                    $this->moveNW();
                    break;

                default:
                    throw new Exception('Unkown step "' . $step . '"');
            }
        }
    }
}

$map = new Map($input);
$map->walk();

echo ('result: ' . (abs($map->x) + abs($map->y)) . "\n");
