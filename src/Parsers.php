<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($file, $extension)
{
    if (!is_object($file)) {
        if ($extension == "json") {
            $file = json_decode($file);
        } elseif ($extension == "yml") {
            $file = Yaml::parse($file, Yaml::PARSE_OBJECT_FOR_MAP);
        }
    }

    $arrFile = (array) $file;
    $result = [];

    foreach ($arrFile as $k => $item) {
        if (is_object($item)) {
            $result[$k] = parse($item, $extension);
        } else {
            $result[$k] = $item;
        }
    }

    return $result;
}
