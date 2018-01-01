<?php

class Duet2 extends Worker
{
    public $program_id;
    public $instructions;
    public $instructions_count;
    public $registers;
    public $last_frequency = null;
    public $current_instruction = 0;
    public $coordinator;
    public $waiting = false;
    public $kill = false;
    public $snd_count = 0;

    // Need to create a register object, and use shift etc to add/ remove properties, instead of using an array (which doesn't work for multi-threaded processes)
    public function __construct($input, $program_id, $coordinator, $registers)
    {
        $this->registers = $registers;
        $this->program_id = $program_id;
        $this->instructions = explode("\n", $input);
        $this->instructions_count = count($this->instructions);
        $this->coordinator = $coordinator;
        $this->setRegisterValue('p', $program_id);
    }

    public function run()
    {
        echo ('Started program: ' . $this->program_id . "\n");

        /*if ($this->program_id == 0) {
            $this->wait();
            echo ('Waiting program: ' . $this->program_id . "\n");
        }*/

        while ($this->current_instruction >= 0 && $this->current_instruction < $this->instructions_count && !$this->kill) {
            //echo ('process instruction "' . $this->current_instruction . '": ' . $this->instructions[$this->current_instruction] . "\n");

            $this->process($this->instructions[$this->current_instruction]);
            //print_r($this);
        }

        //echo ('exited loop' . "\n");
        $this->waiting = true;
    }

    public function setRegisterValue($register, $value)
    {
        $this->registers[$register] = $value;

        //echo ('register "' . $register . '" set to value "' . $value . '"' . "\n");
    }

    public function getValue($argument)
    {
        if (!is_numeric($argument)) {
            if (!isset($this->registers[$argument])) {
                $this->setRegisterValue($argument, 0);
            }

            return $this->registers[$argument];
        } else {
            return $argument;
        }
    }

    public function process($instruction)
    {
        $instruction_parts = explode(' ', $instruction);
        $command = array_shift($instruction_parts);

        //echo ('run command: ' . $command . "\n");
        //print_r($instruction_parts);

        switch ($command) {
            case 'snd':
                //$this->last_frequency = $this->getValue($instruction_parts[0]);
                $this->coordinator->sendToProgram(!$this->program_id, $this->getValue($instruction_parts[0]));
                $this->current_instruction++;
                $this->snd_count++;
                break;

            case 'set':
                $this->setRegisterValue(
                    $instruction_parts[0],
                    $this->getValue($instruction_parts[1])
                );
                $this->current_instruction++;
                break;

            case 'add':
                $this->setRegisterValue(
                    $instruction_parts[0],
                    (float) $this->getValue($instruction_parts[0]) + $this->getValue($instruction_parts[1])
                );
                $this->current_instruction++;
                break;

            case 'mul':
                $this->setRegisterValue(
                    $instruction_parts[0],
                    (float) $this->getValue($instruction_parts[0]) * $this->getValue($instruction_parts[1])
                );
                $this->current_instruction++;
                break;

            case 'mod':
                $this->setRegisterValue(
                    $instruction_parts[0],
                    fmod((float) $this->getValue($instruction_parts[0]), $this->getValue($instruction_parts[1]))
                );
                $this->current_instruction++;
                break;

            case 'rcv':
                //if ($this->getValue($instruction_parts[0]) !== 0) {
                //    die('last f: ' . $this->last_frequency);
                //}
                //$this->current_instruction++;
                try {
                    $value = $this->coordinator->readForProgram($this->program_id);
                    $this->setRegisterValue(
                        $instruction_parts[0],
                        $this->getValue($value)
                    );
                    $this->current_instruction++;
                    $this->waiting = false;
                } catch (Exception $e) {
                    //$this->wait();
                    $this->waiting = true;
                }
                break;

            case 'jgz':
                if ($this->getValue($instruction_parts[0]) > 0) {
                    $this->current_instruction += $this->getValue($instruction_parts[1]);
                } else {
                    $this->current_instruction++;
                }
                break;

            default:
                throw new Exception('Unkown command "' . $command . '"');
        }
    }
}