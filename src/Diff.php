<?php

namespace Differ\Differ;

use function Differ\Parsers\parseToArray;
use function Functional\sort;
use function Differ\Differ\Formatters\getFormatter;


function genDiff($firstFilePath, $secondFilePath, $formatName = 'stylish')
{
    $firstFileFormat = getFormat($firstFilePath);
    $secondFileFormat = getFormat($secondFilePath);

    $firstFileData = parseToArray(getRealPath($firstFilePath), $firstFileFormat);
    $secondFileData = parseToArray(getRealPath($secondFilePath), $secondFileFormat);

    return getFormatter($formatName, getAST($firstFileData, $secondFileData));
}

function getAST(array $dataFromFirstFile, array $dataFromSecondFile): array
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
                'children' => getAST($dataFromFirstFile[$key], $dataFromSecondFile[$key])
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

function getRealPath($filePath)
{
    $path1 = $filePath;
    $path2 = __DIR__ . $filePath;
    $path3 = __DIR__ . "/../tests/fixtures/{$filePath}";

    if (file_exists($path1)) {
        return $path1;
    } elseif (file_exists($path2)) {
        return $path2;
    } else {
        return $path3;
    }
}

function getFormat($path): string
{
    return pathinfo($path, PATHINFO_EXTENSION);
}
