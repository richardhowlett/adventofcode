<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = <<<EOT
p=<3,0,0>, v=<2,0,0>, a=<-1,0,0>
p=<4,0,0>, v=<0,0,0>, a=<-2,0,0>
EOT;
}

require './classes/Vector.class.php';
require './classes/Particle.class.php';

$particle_datas = explode("\n", $input);

$min_acceleration_index = null;
$min_acceleration_scale = null;
foreach ($particle_datas as $particle_index => $particle_data) {
    $matches = array();
    preg_match(
        '/p=<([-0-9]+),([-0-9]+),([-0-9]+)>, v=<([-0-9]+),([-0-9]+),([-0-9]+)>, a=<([-0-9]+),([-0-9]+),([-0-9]+)>/',
        $particle_data,
        $matches
    );

    $position = new Vector($matches[1], $matches[2], $matches[3]);
    $velocity = new Vector($matches[4], $matches[5], $matches[6]);
    $acceleration = new Vector($matches[7], $matches[8], $matches[9]);

    $particle = new Particle($position, $velocity, $acceleration);

    $acceleration_scale = $acceleration->getScale();

    //echo ('particle: ' . $particle_index . ' - ' . $acceleration_scale . PHP_EOL);

    if ($min_acceleration_scale === null || $acceleration_scale < $min_acceleration_scale) {
        $min_acceleration_scale = $acceleration_scale;
        $min_acceleration_index = $particle_index;
    }
}

echo ('result: ' . $min_acceleration_index . PHP_EOL);
