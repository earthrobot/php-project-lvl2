<?php

namespace Gendiff\src\Gendiff;

function gendiff($arr1, $arr2)
{
    ksort($arr1);
    ksort($arr2);

    $diff = '';

    foreach ($arr1 as $k1 => $item1) {
        if (!array_key_exists($k1, $arr2)) {
            $diff .= "- " . $k1 . ": " . $item1 . "\n";
            unset($arr1[$k1]);
        } else {
            foreach ($arr2 as $k2 => $item2) {
                if (!array_key_exists($k2, $arr1)) {
                    $diff .= "+ " . $k2 . ": " . $item2 . "\n";
                    unset($arr2[$k2]);
                } elseif ($k1 == $k2 && $item1 == $item2) {
                    $diff .= $k1 . ": " . $item1 . "\n";
                } elseif ($k1 == $k2 && $item1 != $item2) {
                    $diff .= "- " . $k1 . ": " . $item1 . "\n";
                    $diff .= "+ " . $k2 . ": " . $item2 . "\n";
                }
            }
        }
    }

    return "{\n" . $diff . "}";
}
