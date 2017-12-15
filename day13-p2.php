<?php

ini_set('memory_limit', '512M');
define('DEBUG', false);
//define('DEBUG', true);

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

class Firewall
{
    public $firewall = array();
    public $max_depth = 0;
    public $current_packet_column = -1;
    public $severity = 0;
    public $caught_count = 0;
    public $debug = false;
    public $delay = 0;

    public function __construct($input, $delay = 0, $debug = false)
    {
        $this->debug = $debug;
        //$this->current_packet_column -= $delay;
        $this->delay = $delay;

        $firewall_definition_strings = explode("\n", $input);

        $firewall_definitions = array();
        foreach ($firewall_definition_strings as $firewall_definition_string) {
            $parts = explode(': ', $firewall_definition_string);
            $firewall_definitions[$parts[0]] = $parts[1];
        }

        $max_firewall_column_index = max(array_keys($firewall_definitions));

        for ($i = 0; $i <= $max_firewall_column_index; $i++) {
            $firewall_column = array();

            // if we have a scanner, add it's depth of elements
            if (isset($firewall_definitions[$i])) {
                $firewall_column['depth'] = $firewall_definitions[$i];

                if ($firewall_column['depth'] > $this->max_depth) {
                    $this->max_depth = $firewall_column['depth'];
                }
                /*for ($j = 0; $j < $firewall_column['depth']; $j++) {
                    $firewall_column['scanner_positions'][$j] = false;
                }*/

                // set he initial scanner position as the first element
                //$firewall_column['scanner_positions'][0] = true;

                // calculate the position based on delay
                if ($firewall_column['depth'] > 1) {
                    $half_scans = ($delay / ($firewall_column['depth'] - 1));
                    if ($half_scans % 2 == 0) {
                        $firewall_column['current_scanner_position'] = 0;
                        $firewall_column['scanner_position_change'] = 1;
                    } else {
                        $firewall_column['current_scanner_position'] = $firewall_column['depth'] - 1;
                        $firewall_column['scanner_position_change'] = -1;
                    }
                } else {
                    $firewall_column['scanner_position_change'] = 0;
                }

                $partial_scan = $delay % ($firewall_column['depth'] - 1);
                $firewall_column['current_scanner_position'] += ($partial_scan * $firewall_column['scanner_position_change']);





                /*$firewall_column['current_scanner_position'] = 0;

                if ($firewall_column['depth'] > 1) {
                    $firewall_column['scanner_position_change'] = 1;
                } else {
                    $firewall_column['scanner_position_change'] = 0;
                }*/
            } else {
                // add a single depth, with no active scanner position
                //$this->firewall[$i][0] = false;
            }

            $this->firewall[$i] = $firewall_column;
        }

        if ($this->debug) {
            echo ('Initial State: ' . "\n");
            $this->printFirewall();
        }
    }

    function printFirewall()
    {
        $output = '';
        foreach ($this->firewall as $column => $scanner) {
            $output .= str_pad($column, 4, ' ', STR_PAD_BOTH);
        }

        $output .= "\n";
        for ($i = 0; $i < $this->max_depth; $i++) {
            foreach ($this->firewall as $column => $scanner) {
                if (isset($scanner['current_scanner_position'])) {
                    if ($scanner['depth'] > $i) {
                        if ($scanner['current_scanner_position'] == $i) {
                            $column_output = 'S';
                        } else {
                            $column_output = ' ';
                        }
    
                        if ($column == $this->current_packet_column && $i == 0) {
                            $output .= '(' . $column_output . ') ';
                        } else {
                            $output .= '[' . $column_output . '] ';
                        }
                    } else {
                        $output .= '    ';
                    }
                } elseif ($i == 0) {
                    if ($column == $this->current_packet_column) {
                        $output .= '(.) ';
                    } else {
                        $output .= '... ';
                    }
                } else {
                    $output .= '    ';
                }
            }
            $output .= "\n";
        }

        echo $output . "\n";
    }

    function isCaught()
    {
        if (isset($this->firewall[$this->current_packet_column]['current_scanner_position']) &&
            $this->firewall[$this->current_packet_column]['current_scanner_position'] == 0
        ) {
            return true;
        } else {
            return false;
        }


        if (!isset($this->firewall[$this->current_packet_column]['scanner_positions'])) {
            return false;
        }

        if ($this->firewall[$this->current_packet_column]['scanner_positions'][0] == true) {
            return true;
        } else {
            return false;
        }
    }

    function walk()
    {
        $this->current_packet_column++;

        if ($this->debug) {
            echo ('Picosecond ' . ($this->delay + $this->current_packet_column) . ': ' . "\n");
            $this->printFirewall();
        }

        if ($this->isCaught()) {
            $severity = $this->current_packet_column * $this->firewall[$this->current_packet_column]['depth'];

            $message = 'Caught in column: ' . $this->current_packet_column . ', severity: ' . $severity . "\n";

            if ($this->debug) {
                echo ($message);
            }

            throw new Exception($message);

            $this->severity += $severity;
            $this->caught_count++;
        }

        foreach ($this->firewall as $firewall_column_index => &$firewall_column) {
            //echo ('update firewall_column: ' . $firewall_column_index . "\n");
            //print_r($firewall_column);

            // if this firewall column has a scanner, move it
            if (isset($firewall_column['current_scanner_position'])) {
                //echo ('have scanner, update it' . "\n");
//                $scanner_index = array_search(true, $firewall_column['scanner_positions']);

                $scanner_index = $firewall_column['current_scanner_position'];

                if ($scanner_index !== false) {
                    //echo ('scanner index: ' . $scanner_index . "\n");

                    if ($scanner_index == 0) {
                        //$scanner_index = 0;
                        $firewall_column['scanner_position_change'] = 1;
                    }

                    if ($scanner_index == ($firewall_column['depth'] - 1)) {
                        //$scanner_index = $firewall_column['depth'] - 1;
                        $firewall_column['scanner_position_change'] = -1;
                    }

                    //$scanner_index += $firewall_column['scanner_position_change'];
                    $firewall_column['current_scanner_position'] += $firewall_column['scanner_position_change'];

                    //echo ('new scanner index: ' . $firewall_column['current_scanner_position'] . "\n");
                    /*foreach ($firewall_column['scanner_positions'] as $scanner_position_index => &$scanner_position) {
                        if ($scanner_position_index == $scanner_index) {
                            $scanner_position = true;
                        } else {
                            $scanner_position = false;
                        }
                    }*/
                } else {
                    //echo ('no scanner index' . "\n");
                }
            }
        }

        if ($this->debug) {
            $this->printFirewall();
        }
    }

    function doWalk()
    {
        //$walk_count = count($this->firewall) + $this->delay;
        $walk_count = count($this->firewall);
        for ($i = 0; $i < $walk_count; $i++) {
            $this->walk();
        }

        return $this->caught_count;
    }
}

$delay = 2600000;
//$delay = 0;
while(true) {
    if ($delay % 10000 == 0) {
        echo ('delay: ' . $delay . "\n");
    }

    $firewall = new Firewall($input, $delay, DEBUG);

    try {
        $result = $firewall->doWalk();

        echo ('delay: ' . $delay . ' - ' . $result . "\n");

        if ($result == 0) {
            die ('done');
        }
    } catch (Exception $e) {
        //echo ($e->getMessage());
    }

    $delay++;
}
