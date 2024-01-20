<?php

namespace Formatters\Stylish;

function getStylishFormat($value, string $replacer = ' ', int $spacesCount = 4, int $offsetLeft = 2): string
{
    $iter = function ($currentValue, $depth) use (&$iter, $replacer, $spacesCount, $offsetLeft) {

        if (!array_key_exists('children', $currentValue)) {
            return $currentValue['value'];
        }

        $indent = $depth * $spacesCount - $offsetLeft;
        $currentIndent = str_repeat($replacer, $indent) . $offsetLeft;
        $bracketIndent = str_repeat($replacer, $indent);

        $lines = array_map(
            fn($val) => "{$currentIndent}{$val['key']}: ",
            $currentValue
        )
    }

    return $iter($value, 1);
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

function toString($value) {
    return trim(var_export($value, true), "'");
}