<?php

ini_set('memory_limit', '512M');

if (isset($argv[1])) {
    $required_program_id = $argv[1];
} else {
    $required_program_id = '0';
}

if (isset($argv[2])) {
    $input = $argv[2];
} else {
    $input = '';
}

class ProgramNetwork
{
    public $programs;
    public $linked_programs = array();

    public function __construct($input)
    {
        $program_definitions = explode("\n", $input);

        $this->programs = array();
        foreach ($program_definitions as $program_definition) {
            $parts = explode(' <-> ', $program_definition);
    
            $program_id = $parts[0];
            $linked_program_ids = explode(', ', $parts[1]);
    
            if (!isset($this->programs[$program_id])) {
                $this->programs[$program_id] = array();
            }
    
            foreach ($linked_program_ids as $linked_program_id) {
                if (!isset($this->programs[$linked_program_id])) {
                    $this->programs[$linked_program_id] = array();
                }
    
                // add a link from this program to the linked program
                $this->programs[$program_id][$linked_program_id] = $linked_program_id;
    
                // add a link from the linked program back to this program
                $this->programs[$linked_program_id][$program_id] = $program_id;
            }
        }
    }

    function resetLinkedPrograms()
    {
        $this->linked_programs = array();
    }

    function findLinkedPrograms($program_id)
    {
        if (!isset($this->linked_programs[$program_id])) {
            $this->linked_programs[$program_id] = $program_id;

            foreach ($this->programs[$program_id] as $linked_program_id) {
                $this->findLinkedPrograms($linked_program_id);
            }
        }
    }

    function findGroups()
    {
        $unknown_group_programs = array_keys($this->programs);

        $groups_found = 0;
        while (count($unknown_group_programs) > 0) {
            $unknown_group_program = array_pop($unknown_group_programs);

            $this->resetLinkedPrograms();
            $this->findLinkedPrograms($unknown_group_program);

            $unknown_group_programs = array_diff($unknown_group_programs, $this->linked_programs);

            $groups_found++;
        }

        return $groups_found;
    }
}

$network = new ProgramNetwork($input);
$network->resetLinkedPrograms();
$result = $network->findGroups();

echo ('result: ' . $result . "\n");
