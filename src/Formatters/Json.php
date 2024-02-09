<?php

namespace Differ\Formatters\Json;

function getJson(array $tree)
{
    return json_encode($tree, JSON_PRETTY_PRINT);
}
