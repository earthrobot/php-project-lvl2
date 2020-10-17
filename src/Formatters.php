<?php

namespace Differ\Formatters;

function format(array $diff, $format)
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
