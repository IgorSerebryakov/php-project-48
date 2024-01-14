<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parseToArray(string $path, string $format): array
{
    switch ($format) {
        case "json":
            $array = json_decode(file_get_contents($path), true);
            break;
        case ("yml" || "yaml"):
            $array = Yaml::parseFile($path);
            break;
    }

    return $array;
}