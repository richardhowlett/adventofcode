<?php

class Vector
{
    public $x;
    public $y;
    public $z;

    public function __construct($x, $y, $z)
    {
        $this->x = $x;
        $this->y = $y;
        $this->z = $z;
    }

    public function getScale()
    {
        return abs($this->x) + abs($this->y) + abs($this->z);
        //return abs($this->x + $this->y + $this->z);
    }
}
