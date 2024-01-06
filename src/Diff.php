<?php

namespace Differ\Differ;

$autoloadPath1 = __DIR__ . '/../../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';

if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

use function Functional\sort;

function genDiff($firstPath, $secondPath)
{
    
}

function getDiff($dataFromFirstFile, $dataFromSecondFile)
{
    $decodedFirstFile = getJsonDecode($dataFromFirstFile);
    $decodedSecondFile = getJsonDecode($dataFromSecondFile);

    $mergedFiles = array_merge($decodedFirstFile, $decodedSecondFile);
    $keys = array_keys($mergedFiles);
    $sortedKeys = sort($keys, fn($left, $right) => strcmp($left, $right));

    $diff = array_reduce($sortedKeys, function ($acc, $key) use ($decodedFirstFile, $decodedSecondFile) {
        if (!array_key_exists($key, $decodedSecondFile)) {
            $acc[] = "  - {$key}: " . getValue($decodedFirstFile[$key]);
        } elseif (!array_key_exists($key, $decodedFirstFile)) {
            $acc[] = "  + {$key}: " . getValue($decodedSecondFile[$key]);
        } elseif ($decodedFirstFile[$key] !== $decodedSecondFile[$key]) {
            $acc[] = "  - {$key}: " . getValue($decodedFirstFile[$key]);
            $acc[] = "  + {$key}: " . getValue($decodedSecondFile[$key]);
        } else {
            $acc[] = "    {$key}: " . getValue($decodedFirstFile[$key]);
        }

        return $acc;
    }, []);

    return "{\n" . implode("\n", $diff) . "\n}";
}

$firstPath = '{
    "host": "hexlet.io",
    "timeout": 50,
    "proxy": "123.234.53.22",
    "follow": false
}';

$secondPath = '{
    "timeout": 20,
    "verbose": true,
    "host": "hexlet.io"
}';

//gendiff filepath1.json filepath2.json
//
//{
//    - follow: false
//      host: hexlet.io
//    - proxy: 123.234.53.22
//    - timeout: 50
//    + timeout: 20
//    + verbose: true
//}

function getJsonDecode($file)
{
    return json_decode($file, true);
}

function getJsonEncode($file)
{
    return json_encode($file);
}

print_r(getDiff($firstPath, $secondPath));

function getValue($value)
{
    return is_bool($value) ? var_export($value, true) : $value;
}

