<?php

use Symfony\Component\Yaml\Yaml;
use function Differ\Parsers\parseToArray;

$file1 = [
    "common" => [
        "setting1" => "Value 1",
        "setting2" => 200,
        "setting3" => true,
        "setting6" => [
            "key" => "value",
            "doge" => [
                "wow" => ""
            ]
        ]
    ],
    "group1" => [
        "baz" => "bas",
        "foo" => "bar",
        "nest" => [
            "key" => "value"
        ]
    ],
    "group2" => [
        "abc" => 12345,
        "deep" => [
            "id" => 45
        ]
    ]
];

$file2 = [
    "common" => [
        "follow" => false,
        "setting1" => "Value 1",
        "setting3" => null,
        "setting4" => "blah blah",
        "setting5" => [
            "key5" => "value5"
        ],
        "setting6" => [
            "key" => "value",
            "ops" => "vops",
            "doge" => [
                "wow" => "so much"
            ]
        ]
    ],
    "group1" => [
        "foo" => "bar",
        "baz" => "bars",
        "nest" => "str"
    ],
    "group3" => [
        "deep" => [
            "id" => [
                "number" => 45
            ]
        ],
        "fee" => 100500
    ]
];

$mergedFiles = array_merge($file1, $file2);
$keys = array_keys($mergedFiles);

$arr1 = ['key' => 5];
$arr2 = [];

function parseToArray1(string $path, string $format)
{
    switch ($format) {
        case "json":
            $array = json_decode(file_get_contents($path), true);
            break;
    }

    return $array;
}

var_dump(parseToArray1('/home/xen/php-project-48/tests/fixtures/file2.json', 'json'));