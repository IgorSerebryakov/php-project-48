<?php


use function Functional\flatten;
function toString($value)
{
    return trim(var_export($value, true), "'");
}

// BEGIN
function getCurrentSpacesWithoutLeftShit(int $depth, int $leftShift = 0): string
{
    $countSpaces = 4;
    $currentCountSpaces = $depth * $countSpaces - $leftShift;

    return str_repeat(' ', $currentCountSpaces);
}

function getArrayValueToString(array $value, int $depth): string
{
    $currentValue = $value['value'];
    $newDepth = $depth + 1;

    $iter = function ($currentValue) use (&$iter, $newDepth) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $currentIndent = getCurrentSpacesWithoutLeftShit($newDepth);
        $bracketIndent = $currentIndent;

        $lines = array_map(
            fn($key, $val) => "{$currentIndent}{$key}: {$iter($val, $newDepth + 1)}",
            array_keys($currentValue),
            $currentValue
        );

        $result = ['{', ...$lines, "{$bracketIndent}}"];

        return implode("\n", $result);
    };

    return $iter($currentValue);
}

$data = [
    'key' => 'group3',
    'value' => [
        'deep' => [
            'id' => [
                'number' => 45,
            ],
        ],
        'fee' => 100500,
    ],
    'type' => 'array',
    'status' => 'added',
];

print_r(getArrayValueToString($data, 1));