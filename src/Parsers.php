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

function getRealPath(string $path): string
{
    $fileName = basename($path);
    return __DIR__ . "/../tests/fixtures/{$fileName}";
}

function getFormat(string $path): string
{
    return pathinfo($path, PATHINFO_EXTENSION);
}
