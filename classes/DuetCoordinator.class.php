<?php

class DuetCoordinator
{
    public $duet_a;
    public $duet_b;
    public $comm_channel = array(
        array(),
        array(),
    );

    public function __construct($input)
    {
        $this->register_a = new DuetRegister();
        $this->register_b = new DuetRegister();
        $this->comm_channel = array(
            new DuetRegister(),
            new DuetRegister(),
        );
        $this->duet_a = new Duet2($input, 0, $this, $this->register_a);
        $this->duet_b = new Duet2($input, 1, $this, $this->register_b);
    }

    public function sendToProgram($program_id, $value)
    {
        //echo ('sendToProgram: ' . $program_id . ' - ' . $value . "\n");
        $this->comm_channel[$program_id][] = $value;

        //echo ('comm_data: ' . "\n");
        //print_r($this->comm_channel);
    }

    public function readForProgram($program_id)
    {
        if (count($this->comm_channel[$program_id])) {
            return $this->comm_channel[$program_id]->shift();
        } else {
            throw new Exception ('Comm Channel for Program ID "' . $program_id . '" is empty');
        }
    }

    public function run()
    {
        $this->duet_a->start();
        $this->duet_b->start();

        while(true) {
            echo ('A is waiting: ' . $this->duet_a->waiting . "\n");
            echo ('B is waiting: ' . $this->duet_b->waiting . "\n");

            if ($this->duet_a->waiting && $this->duet_b->waiting) {
                echo ('comm_channel: ' . "\n");
                print_r($this->comm_channel);
                $this->duet_a->kill = true;
                $this->duet_b->kill = true;
                break;
            }

            usleep(100000);
        }

        echo ('snd count a: ' . $this->duet_a->snd_count . "\n");
        echo ('snd count b: ' . $this->duet_b->snd_count . "\n");
    }
}
