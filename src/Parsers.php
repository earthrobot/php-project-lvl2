<?php

namespace Gendiff\src\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($file, $filename)
{
    if (strpos($filename, '.json')) {
        $fileContent = json_decode($file);
    } elseif (strpos($filename, '.yml')) {
        $fileContent = Yaml::parse($file, Yaml::PARSE_OBJECT_FOR_MAP);
    }
    
    return (array) $fileContent;
}
