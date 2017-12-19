<?php

class Dance
{
    public $dancers;

    public function __construct($dancers)
    {
        $this->dancers = $dancers;
    }

    public function dance($dance_moves)
    {
        foreach ($dance_moves as $dance_move) {
            $this->doDanceMove($dance_move);
        }

        //return implode('', $this->dancers);
    }

    public function getDancePositions()
    {
        return implode('', $this->dancers);
    }

    public function doSpin($number_to_spin)
    {
        //echo ('doSpin: ' . $number_to_spin . "\n");

        $positions_to_move = array_slice($this->dancers, -$number_to_spin);
        $remaining_positions = array_slice($this->dancers, 0, count($this->dancers) - $number_to_spin);

        $this->dancers = array_merge($positions_to_move, $remaining_positions);
    }

    public function doExchange($a, $b)
    {
        //echo ('doExchange: ' . $a . '/' . $b . "\n");

        $a_dancer = $this->dancers[$a];
        $b_dancer = $this->dancers[$b];

        $this->dancers[$a] = $b_dancer;
        $this->dancers[$b] = $a_dancer;
    }

    public function doPartner($a_dancer, $b_dancer)
    {
        //echo ('doPartner: ' . $a_dancer . '/' . $b_dancer . "\n");

        $a = array_search($a_dancer, $this->dancers);
        $b = array_search($b_dancer, $this->dancers);

        $this->dancers[$a] = $b_dancer;
        $this->dancers[$b] = $a_dancer;
    }

    public function doDanceMove($dance_move)
    {
        $routine = substr($dance_move, 0, 1);
        $arguments = substr($dance_move, 1);

        switch ($routine) {
            case 's':
                $this->doSpin($arguments);
                break;

            case 'x':
                $parts = explode('/', $arguments);

                $this->doExchange($parts[0], $parts[1]);
                break;

            case 'p':
                $parts = explode('/', $arguments);

                $this->doPartner($parts[0], $parts[1]);
                break;

            default:
                throw new Exception ('Unkown dance move "' . $move . '"');
        }
    }
}