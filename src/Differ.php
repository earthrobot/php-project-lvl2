<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\format;
use function Funct\Collection\union;

function getParsedData($filePath)
{
    $pathParts = pathinfo($filePath);

    $fileContent = file_get_contents($filePath);
    $parsedData = parse($fileContent, $pathParts['extension']);

    return $parsedData;
}

function buildDiff($data1, $data2)
{
    $old = get_object_vars($data1);
    $new = get_object_vars($data2);
    $allKeys = union(array_keys($old), array_keys($new));
    sort($allKeys);

    $diff = array_map(function ($key) use ($old, $new) {
        if (!array_key_exists($key, $new)) {
            return [
                "key" => $key,
                "type" => "deleted",
                "value" => $old[$key]
            ];
        }
        if (!array_key_exists($key, $old)) {
            return [
                "key" => $key,
                "type" => "added",
                "value" => $new[$key]
            ];
        }
        if (is_object($new[$key]) && is_object($old[$key])) {
            return [
                "key" => $key,
                "type" => "nested",
                "children" => buildDiff($old[$key], $new[$key])
            ];
        }
        if ($new[$key] !== $old[$key]) {
            return [
                "key" => $key,
                "type" => "changed",
                "value" => $new[$key],
                "oldValue" => $old[$key]
            ];
        }

        return [
            "key" => $key,
            "type" => 'unchanged',
            "value" => $new[$key]
        ];
    }, $allKeys);
    
    return $diff;
}

function genDiff($filePath1, $filePath2, $format = "pretty")
{
    $parsedFile1 = getParsedData($filePath1);
    $parsedFile2 = getParsedData($filePath2);
       
    $diff = buildDiff($parsedFile1, $parsedFile2);

    return format($diff, $format);
}
