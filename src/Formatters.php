<?php

namespace Differ\Formatters;

use function Differ\Formatters\Pretty;
use function Differ\Formatters\Plain;
use function Differ\Formatters\Json;

function formatPrint(array $diff, $format)
{
    switch ($format) {
        case "plain":
            return Plain\render($diff);
        case "json":
            return Json\render($diff);
        case "pretty":
            return Pretty\render($diff);
        default:
            throw new \Exception("Unknown format: {$format}");
    }
}
