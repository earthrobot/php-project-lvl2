<?php

namespace Gendiff\src\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($file, $filename)
{
    if (!is_object($file)) {
        if (strpos($filename, '.json')) {
            $file = json_decode($file);
        } elseif (strpos($filename, '.yml')) {
            $file = Yaml::parse($file, Yaml::PARSE_OBJECT_FOR_MAP);
        }
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

    return $result;
}
