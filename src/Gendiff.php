<?php

namespace Gendiff\src\Gendiff;
use function Gendiff\src\Parsers\parse;
use function Gendiff\src\Formatters\diffPrint;
use function Gendiff\src\Formatters\diffDescribe;
use function Gendiff\src\Formatters\diffJson;

function getTree($fileName)
{
    if (strpos($fileName, "/") == true) {
        $filePath = $fileName;
    } else {
        $filePath = __DIR__ . '/../tests/fixtures/' . $fileName;
    }
    $fileContent = file_get_contents($filePath);
    $parsedFile = parse($fileContent, $fileName);
    return $parsedFile;
}

function buildDiff(array $arr1, array $arr2, $format = "pretty")
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

function genDiff($file1, $file2, $format = "pretty")
{
    $arr1 = getTree($file1);
    $arr2 = getTree($file2);
   
    $diff = buildDiff($arr1, $arr2, $format);

    if ($format == "plain") {
        return diffDescribe($diff);
    } elseif ($format == "json") {
        return diffJson($diff);
    } else {
        return "{\n" . diffPrint($diff) . "\n}";
    }
}
