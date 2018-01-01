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

function buildTree($root_index) {
    global $all_nodes;

    $tree = $all_nodes[$root_index];
    foreach ($tree['children'] as $index => &$t) {
        $t = buildTree($t);
    }

    return $tree;
}

$tree = buildTree($root_index);

function getTreeWeight($tree) {
    $weight = $tree['weight'];
    $weights = array();
    foreach ($tree['children'] as $t_index => $t) {
        $t_weight = getTreeWeight($t);
        $weight += $t_weight;

        $weights[$t_weight][] = $t_index;
    }

    if (count($weights) > 1) {
        $expected_weight = null;
        $incorrect_weight = null;
        $incorrect_node = null;

        foreach ($weights as $w => $w_nodes) {
            if (count($w_nodes) == 1) {
                $incorrect_weight = $w;
                $incorrect_node = current($w_nodes);
            } else {
                $expected_weight = $w;
            }
        }

        $difference = $expected_weight - $incorrect_weight;
        $corrected_weight = $tree['children'][$incorrect_node]['weight'] + $difference;

        die('corrected_weight: ' . $corrected_weight . "\n");
    }

    return $weight;
}

$weight = getTreeWeight($tree);
