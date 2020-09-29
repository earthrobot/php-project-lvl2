<?php

namespace Gendiff\src\Formatters;

function diffJson(array $diff)
{
    return json_encode($diff);
}
