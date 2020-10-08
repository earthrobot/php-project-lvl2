<?php

namespace Differ\Formatters;

use function Differ\Formatters\Pretty\diffPrint as diffPrintPretty;
use function Differ\Formatters\Plain\diffPrint as diffPrintPlain;
use function Differ\Formatters\Json\diffPrint as diffPrintJson;

function formatPrint(array $diff, $format)
{
    switch ($format) {
        case "plain":
            return diffPrintPlain($diff);
        case "json":
            return diffPrintJson($diff);
        case "pretty":
            return "{\n" . diffPrintPretty($diff) . "\n}";
        default:
            return "Unknown format: " . $format;
    }
}
