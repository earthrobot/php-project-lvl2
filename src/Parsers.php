<?php

namespace Gendiff\src\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($file, $filename)
{
    if (strpos($filename, '.json')) {
        if (!is_object($file)) {
            $file = json_decode($file);    
        }
        
        $arrFile = (array) $file;
        $result = [];
        
        foreach ($arrFile as $k => $item) {
            if (is_object($item)) {
                $result[$k] = parse($item, $filename);
            } else {
                $result[$k] = $item;  
            }
        }
    } elseif (strpos($filename, '.yml')) {
        $fileContent = Yaml::parse($file, Yaml::PARSE_OBJECT_FOR_MAP);
        $result = (array) $fileContent;
    }

    return $result;
}
