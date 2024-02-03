<?php


use function Functional\flatten;


function getValue($item)
{
    if ($item === null) {
        return 'null';
    } else {
        return is_array($item) ? "[complex value]" : var_export($item, true);
    }
}

print_r(getValue('vops'));