<?php

namespace Differ\Differ;

use function Differ\Parsers\parseToArray;
use function Functional\first;
use function Functional\sort;

function genDiff($firstFilePath, $secondFilePath, $format = 'stylish')
{
    $firstFileFormat = getFormat($firstFilePath);
    $secondFileFormat = getFormat($secondFilePath);

    $firstFileData = parseToArray($firstFilePath, $firstFileFormat);
    $secondFileData = parseToArray($secondFilePath, $secondFileFormat);

    return getDiff($firstFileData, $secondFileData);
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

function getFormat($path): string
{
    return pathinfo($path, PATHINFO_EXTENSION);
}

function getValue($value)
{
    return is_bool($value) ? var_export($value, true) : $value;
}
