<?php

namespace Differ\Formatters;

use function Differ\Formatters\Json\getJson;
use function Differ\Formatters\Stylish\getStylish;
use function Differ\Formatters\Plain\getPlain;

function getFormatter(string $formatName, array $AST)
{
    return match ($formatName) {
        'stylish' => getStylish($AST),
        'plain' => getPlain($AST),
        'json' => getJson($AST)
    };
}
