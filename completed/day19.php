<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = <<<EOT
     |          
     |  +--+    
     A  |  C    
 F---|----E|--+ 
     |  |  |  D 
     +B-+  +--+ 
EOT;
}

require '../classes/NetworkMap.class.php';

$map = new NetworkMap($input);
$map->printMap();
$map->run();

echo ('result: ' . implode('', $map->visited_nodes) . PHP_EOL);
echo ('steps: ' . $map->step . PHP_EOL);
