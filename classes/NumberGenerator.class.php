<?php

class NumberGenerator
{
    public $value;
    public $factor;
    public $denominator;
    public $validation_denominator;

    public function __construct($value, $factor, $denominator, $validation_denominator = null) {
        $this->value = $value;
        $this->factor = $factor;
        $this->denominator = $denominator;
        $this->validation_denominator = $validation_denominator;
    }

    public function generateNextValue()
    {
        // this function easily hits the max value storable by a php integer, so need to use floating point mod instead of integer.
        $nominator = ($this->value) * $this->factor;
        $remainder = fmod($nominator, $this->denominator);
        $this->value = $remainder;

        if ($this->validation_denominator !== null && fmod($this->value, $this->validation_denominator) != 0) {
            return $this->generateNextValue();
        }

        return $this->value;
    }
}
