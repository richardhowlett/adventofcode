<?php

class Duet
{
    public $instructions;
    public $instructions_count;
    public $registers = array();
    public $last_frequency = null;
    public $current_instruction = 0;

    public function __construct($input)
    {
        $this->instructions = explode("\n", $input);
        $this->instructions_count = count($this->instructions);
    }

    public function run()
    {
        while ($this->current_instruction >= 0 && $this->current_instruction < $this->instructions_count) {
            //echo ('process instruction "' . $this->current_instruction . '": ' . $this->instructions[$this->current_instruction] . "\n");

            $this->process($this->instructions[$this->current_instruction]);
            //print_r($this);
        }

        //echo ('exited loop' . "\n");
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
                $this->last_frequency = $this->getValue($instruction_parts[0]);
                $this->current_instruction++;
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
                if ($this->getValue($instruction_parts[0]) !== 0) {
                    die('last f: ' . $this->last_frequency);
                }
                $this->current_instruction++;
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