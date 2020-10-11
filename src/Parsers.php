<?php

namespace Differ\Parsers;

use Symfony\Component\Yaml\Yaml;

function parse($data, $extension)
{

    if ($extension == "json") {
        $result = json_decode($data);
    } elseif ($extension == "yml") {
        $result = Yaml::parse($data, Yaml::PARSE_OBJECT_FOR_MAP);
    } else {
        throw new Exception("Wrong file extension: {$extension}");
    }

    return $result;
}
