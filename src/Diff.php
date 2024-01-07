<?php

namespace Differ\Differ;

use function Functional\sort;

function genDiff($firstFilePath, $secondFilePath, $format = 'stylish')
{
    $dataFromFirstFile = getContest($firstFilePath);
    $dataFromSecondFile = getContest($secondFilePath);

    $decodedDataFromFirstFile = getJsonDecode($dataFromFirstFile);
    $decodedDataFromSecondFile = getJsonDecode($dataFromSecondFile);

    return getDiff($decodedDataFromFirstFile, $decodedDataFromSecondFile);
}

function getDiff(array $dataFromFirstFile, array $dataFromSecondFile)
{
    $mergedFiles = array_merge($dataFromFirstFile, $dataFromSecondFile);
    $keys = array_keys($mergedFiles);
    $sortedKeys = sort($keys, fn($left, $right) => strcmp($left, $right));

    $diff = array_reduce($sortedKeys, function ($acc, $key) use ($dataFromFirstFile, $dataFromSecondFile) {
        if (!array_key_exists($key, $dataFromSecondFile)) {
            $acc[] = "  - {$key}: " . getValue($dataFromFirstFile[$key]);
        } elseif (!array_key_exists($key, $dataFromFirstFile)) {
            $acc[] = "  + {$key}: " . getValue($dataFromSecondFile[$key]);
        } elseif ($dataFromFirstFile[$key] !== $dataFromSecondFile[$key]) {
            $acc[] = "  - {$key}: " . getValue($dataFromFirstFile[$key]);
            $acc[] = "  + {$key}: " . getValue($dataFromSecondFile[$key]);
        } else {
            $acc[] = "    {$key}: " . getValue($dataFromFirstFile[$key]);
        }

        return $acc;
    }, []);

    return "{\n" . implode("\n", $diff) . "\n}";
}

function getJsonDecode($file)
{
    return json_decode($file, true);
}

function getValue($value)
{
    return is_bool($value) ? var_export($value, true) : $value;
}

function getContest($path)
{
    return file_get_contents($path);
}
