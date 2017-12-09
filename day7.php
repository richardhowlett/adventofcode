<?php

if (isset($argv[1])) {
    $input = $argv[1];
} else {
    $input = '';
}

$nodes = explode("\n", $input);

$all_nodes = array();
$all_children = array();
foreach ($nodes as $node) {
    $parts = explode(' -> ', $node);

    preg_match('/(.+) \(([0-9]+)\)/', $node, $matches);

    $name = $matches[1];
    $weight = $matches[2];

    if (!empty($parts[1])) {
        $tmp_children = explode(', ', $parts[1]);
        $children = array_combine($tmp_children, $tmp_children);
    } else {
        $children = array();
    }

    $all_nodes[$name] = array(
        'name' => $name,
        'weight' => $weight,
        'children' => $children,
    );

    $all_children += $children;
}

$root_node = array_diff(array_keys($all_nodes), $all_children);
$root_index = current($root_node);
echo ('root: ' . $root_index . "\n");
