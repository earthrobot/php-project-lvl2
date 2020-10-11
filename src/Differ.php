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
    $oldArray = get_object_vars($old);
    $newArray = get_object_vars($new);
    $allKeys = array_unique(array_merge(array_keys($oldArray), array_keys($newArray)));
    sort($allKeys);

    $diff = array_map(function ($key) use ($oldArray, $newArray) {
        if (!array_key_exists($key, $newArray)) {
            return [
                "key" => $key,
                "status" => "deleted",
                "value" => $oldArray[$key]
            ];
        } elseif (!array_key_exists($key, $oldArray)) {
            return [
                "key" => $key,
                "status" => "added",
                "value" => $newArray[$key]
            ];
        } elseif (is_object($newArray[$key]) && is_object($oldArray[$key])) {
            return [
                "key" => $key,
                "status" => "nested",
                "children" => buildDiff($oldArray[$key], $newArray[$key])
            ];
        } else {
            if ($newArray[$key] === $oldArray[$key]) {
                return [
                    "key" => $key,
                    "status" => 'unchanged',
                    "value" => $newArray[$key]
                ];
            } else {
                return [
                    "key" => $key,
                    "status" => "changed",
                    "value" => $newArray[$key],
                    "oldValue" => $oldArray[$key]
                ];
            }
        }
    }, $allKeys);

    ksort($diff);
    return $diff;
}

function genDiff($filePath1, $filePath2, $format = "pretty")
{
    $parsedFile1 = getParsedData($filePath1);
    $parsedFile2 = getParsedData($filePath2);
       
    $diff = buildDiff($parsedFile1, $parsedFile2);

    return formatPrint($diff, $format);
}
