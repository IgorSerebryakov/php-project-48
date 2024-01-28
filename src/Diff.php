<?php

namespace Differ\Differ;

use function Differ\Parsers\parseToArray;
use function Formatters\Stylish\getResultStylish;
use function Functional\sort;
use function Formatters\Stylish\getStylish;


function genDiff($firstFilePath, $secondFilePath, $format = 'stylish')
{
    $firstFileFormat = getFormat($firstFilePath);
    $secondFileFormat = getFormat($secondFilePath);

    $firstFileData = parseToArray($firstFilePath, $firstFileFormat);
    $secondFileData = parseToArray($secondFilePath, $secondFileFormat);

    return getResultStylish((getDiffAST($firstFileData, $secondFileData)));
}

function getDiffAST(array $dataFromFirstFile, array $dataFromSecondFile): array
{
    $mergedFiles = [...$dataFromFirstFile, ...$dataFromSecondFile];
    $keys = array_keys($mergedFiles);
    $sortedKeys = sort($keys, fn($left, $right) => strcmp($left, $right));

    return array_map(function ($key) use ($dataFromFirstFile, $dataFromSecondFile) {

        if (!array_key_exists($key, $dataFromFirstFile)) {
            return [
                'key' => $key,
                'value' => $dataFromSecondFile[$key],
                'type' => gettype($dataFromSecondFile[$key]),
                'status' => 'added'
            ];

        } elseif (!array_key_exists($key, $dataFromSecondFile)) {
            return [
                'key' => $key,
                'value' => $dataFromFirstFile[$key],
                'type' => gettype($dataFromFirstFile[$key]),
                'status' => 'deleted'
            ];

        } elseif (is_array($dataFromFirstFile[$key]) && is_array($dataFromSecondFile[$key])) {
            return [
                'key' => $key,
                'status' => 'root',
                'children' => getDiffAST($dataFromFirstFile[$key], $dataFromSecondFile[$key])
            ];

        } elseif ($dataFromFirstFile[$key] !== $dataFromSecondFile[$key]) {
            $oldValue = $dataFromFirstFile[$key];
            $newValue = $dataFromSecondFile[$key];
            $oldType = gettype($oldValue);
            $newType = gettype($newValue);

            return [
                'key' => $key,
                'oldValue' => $oldValue,
                'oldType' => $oldType,
                'newValue' => $newValue,
                'newType' => $newType,
                'status' => 'changed'
            ];

        } else {

            return [
                'key' => $key,
                'value' => $dataFromFirstFile[$key],
                'type' => gettype($dataFromFirstFile[$key]),
                'status' => 'unchanged'
            ];
        }

    }, $sortedKeys);
}

function getFormat($path): string
{
    return pathinfo($path, PATHINFO_EXTENSION);
}

function getValue($value)
{
    return is_bool($value) ? var_export($value, true) : $value;
}
