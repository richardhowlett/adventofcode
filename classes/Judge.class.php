<?php

class Judge
{
    public $generators;

    public function __construct($generators)
    {
        $this->generators = $generators;
    }

    public function getMatchCount($number_of_rounds)
    {
        $match_count = 0;
        for ($round = 0; $round < $number_of_rounds; $round++) {
            if ($round % 100000 == 0) {
                echo ('round: ' . $round . ' - ' . $match_count . "\n");
            }
            $values = array();
            foreach ($this->generators as $generator_name => $generator) {
                $value = $generator->generateNextValue();
                //echo ('value: ' . $value . "\n");
                $values[$this->getComparableValue($value)][$generator_name] = $generator_name;
            }

            //print_r($values);

            if (count($values) == 1) {
                $match_count++;
            }
        }

        echo ('round: ' . $round . ' - ' . $match_count . "\n");

        return $match_count;
    }

    public function getComparableValue($value)
    {
        $binary_value = decbin($value);
        $compare_value = str_pad(substr((string) $binary_value, -16), 16, 0, STR_PAD_LEFT);

        return $compare_value;
    }
}