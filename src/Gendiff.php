<?php

namespace Gendiff\src\Gendiff;

function gendiff($json1, $json2)
{
    $dec_json1 = json_decode($json1);
    $dec_json2 = json_decode($json2);

    $dec_json1_arr = (array) $dec_json1;
    $dec_json2_arr = (array) $dec_json2;

    ksort($dec_json1_arr);
    ksort($dec_json2_arr);

    $diff = '';

    foreach ($dec_json1_arr as $k1 => $item1) {
        if (!array_key_exists($k1, $dec_json2_arr)) {
            $diff .= "- " . $k1 . ": " . $item1 . "\n";
            unset($dec_json1_arr[$k1]);
        } else {
            foreach ($dec_json2_arr as $k2 => $item2) {
                if (!array_key_exists($k2, $dec_json1_arr)) {
                    $diff .= "+ " . $k2 . ": " . $item2 . "\n";
                    unset($dec_json2_arr[$k2]);
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