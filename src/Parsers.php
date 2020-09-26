<?php

namespace Gendiff\src\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($file)
{
    if (!is_object($file)) {
        $file = json_decode($file);    
    }

    $arrFile = (array) $file;
    $result = [];

    foreach ($arrFile as $k => $item) {
        if (is_object($item)) {
            $result[$k] = parse($item);
        } else {
            if (is_bool($item)) {
                if ($item == true) {
                    $result[$k] = 'true';  
                } elseif ($item == false) {
                    $result[$k] = 'false';  
                }
            } else {
                $result[$k] = $item;  
            }
        }
    }

    return $result;
}
