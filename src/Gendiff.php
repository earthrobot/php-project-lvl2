<?php

namespace Differ\Differ;

use function Differ\Parsers\parse;
use function Differ\Formatters\Formatters\formatPrint;

function getTree($filePath)
{
    $pathParts = pathinfo($filePath);
    $extension = $pathParts['extension'];

    $fileContent = file_get_contents($filePath);
    $parsedFile = parse($fileContent, $extension);

    return $parsedFile;
}

function buildDiff(array $arr1, array $arr2)
{
    $diff = [];

    foreach ($arr1 as $k => $item) {
        if (!array_key_exists($k, $arr2)) {
            $diff[$k] = [
                "name" => $k,
                "status" => "deleted",
                "value" => $item
            ];
            unset($arr1[$k]);
        }
    }
    foreach ($arr2 as $k => $item) {
        if (!array_key_exists($k, $arr1)) {
            $diff[$k] = [
                "name" => $k,
                "status" => "added",
                "value" => $item
            ];
            unset($arr2[$k]);
        }
    }

    foreach ($arr1 as $k => $item) {
        $item2 = $arr2[$k];
        if (is_array($item) && is_array($item2)) {
            $diff[$k] = [
                "name" => $k,
                "status" => "nested",
                "children" => buildDiff($item, $item2)
            ];
        } else {
            if ($item === $item2) {
                $diff[$k] = [
                    "name" => $k,
                    "status" => 'unchanged',
                    "value" => $item
                ];
            } else {
                $diff[$k] = [
                    "name" => $k,
                    "status" => "changed",
                    "value" => $item2,
                    "oldValue" => $item
                ];
            }
        }
    }

    ksort($diff);

    return $diff;
}

function genDiff($filePath1, $filePath2, $format = "pretty")
{
    $arr1 = getTree($filePath1);
    $arr2 = getTree($filePath2);
   
    $diff = buildDiff($arr1, $arr2, $format);

    return formatPrint($diff, $format);
}
