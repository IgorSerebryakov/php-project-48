<?php

namespace Formatters\Json;

function getJson($tree)
{
    return json_encode($tree, JSON_PRETTY_PRINT);
}
