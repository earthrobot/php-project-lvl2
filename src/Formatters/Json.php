<?php

namespace Differ\Formatters\Json;

function diffPrint(array $diff)
{
    return json_encode($diff);
}
