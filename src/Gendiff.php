<?php

namespace Gendiff\src\Gendiff;

function gendiff(array $arr1, array $arr2)
{
    ksort($arr1);
    ksort($arr2);
    
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
                "children" => gendiff($item, $item2)
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
