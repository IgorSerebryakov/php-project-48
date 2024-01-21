<?php

namespace Formatters\Stylish;

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

function getArrayValueToString(array $value, int $depth): string
{
    $currentValue = $value['value'];

    $iter = function ($currentValue) use (&$iter, $depth) {
        if (!is_array($currentValue)) {
            return toString($currentValue);
        }

        $currentIndent = getCurrentSpacesWithoutLeftShit($depth);
        $bracketIndent = $currentIndent;

        $lines = array_map(
            fn($key, $val) => "{$currentIndent}{$key}: {$iter($val, $depth + 1)}",
            array_keys($currentValue),
            $currentValue
            );

        $result = ['{', ...$lines, "{$bracketIndent}"];

        return implode("\n", $result);
    };

    return $iter($currentValue);
}

function toString($value)
{
    return trim(var_export($value, true), "'");
}


// Array (
//    0 => Array (
//        'key' => 'common'
//        'status' => 'root'
//        'children' => Array (
//            0 => Array &0 [
//            'key' => 'follow',
//            'value' => false,
//            'type' => 'boolean',
//            'status' => 'added',
//        ]
//            1 => Array &0 [
//            'key' => 'setting1',
//            'value' => 'Value 1',
//            'type' => 'string',
//            'status' => 'unchanged',
//        ]
//            2 => Array &0 [
//            'key' => 'setting2',
//            'value' => 200,
//            'type' => 'integer',
//            'status' => 'deleted',
//        ]
//            3 => Array &0 [
//            'key' => 'setting3',
//            'oldValue' => true,
//            'oldType' => 'boolean',
//            'newValue' => null,
//            'newType' => 'NULL',
//            'status' => 'changed',
//        ]
//            4 => Array &0 [
//            'key' => 'setting4',
//            'value' => 'blah blah',
//            'type' => 'string',
//            'status' => 'added',
//        ]
//            5 => Array &0 [
//            'key' => 'setting5',
//            'value' => Array &1 [
//                'key5' => 'value5',
//            ],
//            'type' => 'array',
//            'status' => 'added',
//        ]
//            6 => Array (
//                'key' => 'setting6'
//                'status' => 'root'
//                'children' => Array (
//                    0 => Array (
//                        'key' => 'doge'
//                        'status' => 'root'
//                        'children' => Array (
//                            0 => Array (
//                                'key' => 'wow'
//                                'oldValue' => ''
//                                'oldType' => 'string'
//                                'newValue' => 'so much'
//                                'newType' => 'string'
//                                'status' => 'changed'
//                            )
//                        )
//                    )
//                    1 => Array &0 [
//                    'key' => 'key',
//                    'value' => 'value',
//                    'type' => 'string',
//                    'status' => 'unchanged',
//                ]
//                    2 => Array &0 [
//                    'key' => 'ops',
//                    'value' => 'vops',
//                    'type' => 'string',
//                    'status' => 'added',
//                ]
//                )
//            )
//        )
//    )
//    1 => Array &0 [
//    'key' => 'group1',
//    'status' => 'root',
//    'children' => Array &1 [
//        0 => Array &2 [
//            'key' => 'baz',
//            'oldValue' => 'bas',
//            'oldType' => 'string',
//            'newValue' => 'bars',
//            'newType' => 'string',
//            'status' => 'changed',
//        ],
//        1 => Array &3 [
//            'key' => 'foo',
//            'value' => 'bar',
//            'type' => 'string',
//            'status' => 'unchanged',
//        ],
//        2 => Array &4 [
//            'key' => 'nest',
//            'oldValue' => Array &5 [
//                'key' => 'value',
//            ],
//            'oldType' => 'array',
//            'newValue' => 'str',
//            'newType' => 'string',
//            'status' => 'changed',
//        ],
//    ],
//]
//    2 => Array &0 [
//    'key' => 'group2',
//    'value' => Array &1 [
//        'abc' => 12345,
//        'deep' => Array &2 [
//            'id' => 45,
//        ],
//    ],
//    'type' => 'array',
//    'status' => 'deleted',
//]
//    3 => Array &0 [
//    'key' => 'group3',
//    'value' => Array &1 [
//        'deep' => Array &2 [
//            'id' => Array &3 [
//                'number' => 45,
//            ],
//        ],
//        'fee' => 100500,
//    ],
//    'type' => 'array',
//    'status' => 'added',
//]
//)

