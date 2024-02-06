<?php

namespace Formatters\Stylish;

function getStylishWithoutBraces(array $tree, int $depth = 1): string
{
    $coll = array_map(function ($val) use ($depth) {
        if ($val['status'] === 'added') {
            return getCurrentSpacesWithLeftShift($depth) . getSpecialWithKey($val, '+') . getValueToString(getValue($val), $depth);
        } elseif ($val['status'] === 'deleted') {
            return getCurrentSpacesWithLeftShift($depth) . getSpecialWithKey($val, '-') . getValueToString(getValue($val), $depth);
        } elseif ($val['status'] === 'unchanged') {
            return getCurrentSpacesWithLeftShift($depth) . getSpecialWithKey($val, ' ') . getValueToString(getValue($val), $depth);
        } elseif ($val['status'] === 'changed') {
            return getCurrentSpacesWithLeftShift($depth) . getSpecialWithKey($val, '-') . getValueToString(getOldValue($val), $depth) . "\n"
                 . getCurrentSpacesWithLeftShift($depth) . getSpecialWithKey($val, '+') . getValueToString(getNewValue($val), $depth);
        } elseif ($val['status'] === 'root') {
            $children = getStylishWithoutBraces(getChildren($val), $depth + 1);
            return getCurrentSpacesWithLeftShift($depth) . getSpecialWithKey($val, ' ') . getChildrenWithBraces($children, $depth);
        }
    }, $tree);

    return implode("\n", $coll);
}

function getStylish($result)
{
    return getBraces(getStylishWithoutBraces($result));
}

function getChildrenWithBraces($children, $depth)
{
    return " {\n{$children}\n" . getCurrentSpacesWithoutLeftShit($depth) . "}";
}

function getSpecialWithKey($value, string $special)
{
    return "{$special} " . getKey($value) . ":";
}

function getKey($tree)
{
    return $tree['key'];
}

function getValue($tree)
{
    return $tree['value'];
}

function getNewValue($tree)
{
    return $tree['newValue'];
}

function getOldValue($tree)
{
    return $tree['oldValue'];
}

function getChildren($tree)
{
    return $tree['children'];
}

function getBraces($result)
{
    return "{\n{$result}\n}";
}

function getCurrentSpacesWithoutLeftShit(int $depth, int $leftShift = 0): string
{
    $countSpaces = 4;
    $currentCountSpaces = $depth * $countSpaces - $leftShift;

    return str_repeat(' ', $currentCountSpaces);
}

function getCurrentSpacesWithLeftShift(int $depth): string
{
    return getCurrentSpacesWithoutLeftShit($depth, 2);
}

function getValueToString($value, $depth = 1)
{
    if (is_array($value)) {
        return getArrayValueToString($value, $depth);
    } else {
        return toString($value);
    }
}

function getArrayValueToString(array $value, $depth = 1): string
{
    $iter = function ($value, $depth) use (&$iter) {
        if (!is_array($value)) {
            return toString($value);
        }

        $currentIndent = getCurrentSpacesWithoutLeftShit($depth);
        $bracketIndent = getCurrentSpacesWithoutLeftShit($depth - 1);

        $lines = array_map(
            fn($key, $val) => "{$currentIndent}{$key}:{$iter($val, $depth + 1)}",
            array_keys($value),
            $value
        );

        $result = [' {', ...$lines, "{$bracketIndent}}"];

        return implode("\n", $result);
    };

    return $iter($value, $depth + 1);
}

function toString($value)
{
    if ($value === "") {
        return " ";
    }
    return $value === null ? ' null' : ' ' . trim(var_export($value, true), "'");
}
