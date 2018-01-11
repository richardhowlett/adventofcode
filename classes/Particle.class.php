<?php

class Particle
{
    public $position;
    public $velocity;
    public $acceleration;

    public function __construct(Vector $position, Vector $velocity, Vector $acceleration)
    {
        $this->position = $position;
        $this->velocity = $velocity;
        $this->acceleration = $acceleration;
    }
}
