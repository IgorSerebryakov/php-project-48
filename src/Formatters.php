<?php

namespace Differ\Differ\Formatters;

use function Formatters\Json\getJson;
use function Formatters\Stylish\getStylish;
use function Formatters\Plain\getPlain;

function getFormatter(string $formatName, array $AST)
{
    switch ($formatName) {
        case "stylish":
            return getStylish($AST);
        case "plain":
            return getPlain($AST);
        case "json":
            return getJson($AST);
    }
}
