<?php

namespace Differ\Formatters;

use function Differ\Formatters\Json\getJson;
use function Differ\Formatters\Stylish\getStylish;
use function Differ\Formatters\Plain\getPlain;

function getFormatter(string $formatName, array $AST)
{
    switch ($formatName) {
        case 'stylish':
            return getStylish($AST);
        case 'plain':
            return getPlain($AST);
        case 'json':
            return getJson($AST);
    }
}
