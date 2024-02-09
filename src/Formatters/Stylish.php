<?php

namespace Differ\Formatters\Stylish;

function getStylishWithoutBraces(array $tree, int $depth = 1): string
{
    $coll = array_map(function ($val) use ($depth) {
        if ($val['status'] === 'added') {
            return getIndent($depth, 2) . getSpecialWithKey($val, '+')
                . getValueToString(getValue($val), $depth);
        } elseif ($val['status'] === 'deleted') {
            return getIndent($depth, 2) . getSpecialWithKey($val, '-')
                . getValueToString(getValue($val), $depth);
        } elseif ($val['status'] === 'unchanged') {
            return getIndent($depth, 2) . getSpecialWithKey($val, ' ')
                . getValueToString(getValue($val), $depth);
        } elseif ($val['status'] === 'changed') {
            return getIndent($depth, 2) . getSpecialWithKey($val, '-')
                . getValueToString(getValue($val, 'oldValue'), $depth) . "\n"
                 . getIndent($depth, 2) . getSpecialWithKey($val, '+')
                . getValueToString(getValue($val, 'newValue'), $depth);
        } elseif ($val['status'] === 'root') {
            $children = getStylishWithoutBraces(getChildren($val), $depth + 1);
            return getIndent($depth, 2) . getSpecialWithKey($val, ' ')
                . getChildrenWithBraces($children, $depth);
        }
    }, $tree);

    return implode("\n", $coll);
}

function getStylish(array $result)
{
    return "{\n" . getStylishWithoutBraces($result) . "\n}";
}

function getChildrenWithBraces(string $children, int $depth)
{
    return " {\n{$children}\n" . getIndent($depth) . "}";
}

function getSpecialWithKey(array $value, string $special)
{
    return "{$special} " . getKey($value) . ":";
}

function getKey(array $tree)
{
    return $tree['key'];
}

function getValue(array $tree, string $valueKey = 'value')
{
    return $tree[$valueKey];
}

function getChildren(array $tree)
{
    return $tree['children'];
}

function getIndent(int $depth, int $leftShift = 0): string
{
    $countSpaces = 4;
    $currentCountSpaces = $depth * $countSpaces - $leftShift;

    return str_repeat(' ', $currentCountSpaces);
}

function getArrayToString(array $value, int $depth = 1): string
{
    $iter = function ($value, $depth) use (&$iter) {
        if (!is_array($value)) {
            return getValueToString($value);
        }

        $currentIndent = getIndent($depth);
        $bracketIndent = getIndent($depth - 1);

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

function getValueToString(mixed $value, int $depth = 1)
{
    if (is_array($value)) {
        return getArrayToString($value, $depth);
    } else {
        if ($value === "") {
            return " ";
        }
        return $value === null ? ' null' : ' ' . trim(var_export($value, true), "'");
    }
}
