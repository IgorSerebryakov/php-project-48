<?php


use function Functional\flatten;
function toString($value)
{
    return trim(var_export($value, true), "'");
}

function getCurrentSpacesWithoutLeftShit(int $depth, int $leftShift = 0): string
{
    $countSpaces = 4;
    $currentCountSpaces = $depth * $countSpaces - $leftShift;

    return str_repeat(' ', $currentCountSpaces);
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
            fn($key, $val) => "{$currentIndent}{$key}: {$iter($val, $depth + 1)}",
            array_keys($value),
            $value
        );

        $result = ['{', ...$lines, "{$bracketIndent}}"];

        return implode("\n", $result);
    };

    return $iter($value, $depth + 1);
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

// print_r(getArrayValueToString($data['value'], 1));

print_r(toString(null));