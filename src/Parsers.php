<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseToArray(string $path, string $format)
{
    if ($format === "json") {
        $content = file_get_contents($path);
        return json_decode((string) $content, true);
    } elseif ($format === "yml" || $format === "yaml") {
        return Yaml::parseFile($path);
    }
}
