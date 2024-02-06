<?php

namespace Formatters\Plain;

use function Functional\flatten;

function getPlain($tree, $key = '')
{

    $coll = array_map(function ($val) use ($key) {

        $currentKey = $key === "" ? "{$val['key']}" : "{$key}{$val['key']}";

        if ($val['status'] === 'added') {
            return "Property " . "'{$currentKey}'" . " was added with value: " . getValue($val['value']);
        } elseif ($val['status'] === 'deleted') {
            return "Property " . "'{$currentKey}'" . " was removed";
        } elseif ($val['status'] === 'changed') {
            return "Property " . "'{$currentKey}'" . " was updated. From " . getValue($val['oldValue']) . " to " . getValue($val['newValue']);
        } elseif ($val['status'] === 'root') {
            $children = $val['children'];
            $key .= "{$val['key']}.";
            return getPlain($children, $key);
        }

        }, $tree);

    return implode("\n", array_filter(flatten($coll)));
}

function getValue($item)
{
    if ($item === null) {
        return 'null';
    } else {
        return is_array($item) ? "[complex value]" : var_export($item, true);
    }
}
