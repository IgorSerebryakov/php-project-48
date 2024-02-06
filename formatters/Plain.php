<?php

namespace Formatters\Plain;

use function Functional\flatten;

function getPlain(array $tree, string $key = '')
{
    $coll = array_map(function ($val) use ($key) {
        
        $currentKey = $key === "" ? "{$val['key']}" : "{$key}.{$val['key']}";

        if ($val['status'] === 'added') {
            return "Property " . "'{$currentKey}'" . " was added with value: " . getValue($val['value']);
        } elseif ($val['status'] === 'deleted') {
            return "Property " . "'{$currentKey}'" . " was removed";
        } elseif ($val['status'] === 'changed') {
            return "Property " . "'{$currentKey}'" . " was updated. From " . getValue($val['oldValue']) .
                " to " . getValue($val['newValue']);
        } elseif ($val['status'] === 'root') {
            $children = $val['children'];
            return getPlain($children, $currentKey);
        }
    }, $tree);

    return implode("\n", array_filter(flatten($coll)));
}

function getValue(mixed $item)
{
    if ($item === null) {
        return 'null';
    } else {
        return is_array($item) ? "[complex value]" : var_export($item, true);
    }
}
