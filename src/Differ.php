<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\formatPrint;

function getParsedData($filePath)
{
    $pathParts = pathinfo($filePath);

    $fileContent = file_get_contents($filePath);
    $parsedFile = parse($fileContent, $pathParts['extension']);

    return $parsedFile;
}

function buildDiff($old, $new)
{
    $old = get_object_vars($old);
    $new = get_object_vars($new);
    $jointArray = array_replace($old, $new);
    ksort($jointArray);
    $diff = [];

    foreach ($jointArray as $k => $item) {
        $oldItem = array_key_exists($k, $old) ? $old[$k] : false;
        if (!array_key_exists($k, $new)) {
            $diff[$k] = [
                "name" => $k,
                "status" => "deleted",
                "value" => $item
            ];
        } elseif (!array_key_exists($k, $old)) {
            $diff[$k] = [
                "name" => $k,
                "status" => "added",
                "value" => $item
            ];
        } elseif (is_object($item) && is_object($oldItem)) {
            $diff[$k] = [
                "name" => $k,
                "status" => "nested",
                "children" => buildDiff($oldItem, $item)
            ];
        } else {
            if ($item === $oldItem) {
                $diff[$k] = [
                    "name" => $k,
                    "status" => 'unchanged',
                    "value" => $item
                ];
            } else {
                $diff[$k] = [
                    "name" => $k,
                    "status" => "changed",
                    "value" => $item,
                    "oldValue" => $oldItem
                ];
            }
        }
    }

    return $diff;
}

function genDiff($filePath1, $filePath2, $format = "pretty")
{
    $parsedFile1 = getParsedData($filePath1);
    $parsedFile2 = getParsedData($filePath2);
   
    $diff = buildDiff($parsedFile1, $parsedFile2);

    return formatPrint($diff, $format);
}
