<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseToArray(string $path)
{
    switch (getFormat($path)) {
        case "json":
            $content = file_get_contents(getRealPath($path));
            return json_decode((string) $content, true);
        case "yml" || "yaml":
            return Yaml::parseFile($path);
    }
}

function getRealPath(string $filePath)
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

//function getRealPath(string $path): string
//{
//    $fileName = basename($path);
//    return __DIR__ . "/../tests/fixtures/{$fileName}";
//}

function getFormat(string $path): string
{
    return pathinfo($path, PATHINFO_EXTENSION);
}
