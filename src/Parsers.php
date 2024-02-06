<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseToArray(string $path, string $format)
{
    if ($format === "json") {
        return json_decode(file_get_contents($path), true);
    } elseif ($format === "yml" || $format === "yaml") {
        return Yaml::parseFile($path);
    }
}
