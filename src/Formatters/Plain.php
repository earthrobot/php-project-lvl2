<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;
use function Funct\Collection\compact;

function findValue($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_int($value)) {
        return "'{strval($value)}'";
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    return "[complex value]";
}

function render(array $diff, $parent = [], $depth = 0)
{
    $result = array_map(function ($item) use ($parent, $depth) {

        $parent[$depth] = $item['key'];
        if ($depth > 0) {
            $path = "'" . implode(".", $parent) . "'";
        } else {
            $path = "'" . $item['key'] . "'";
        }

        switch ($item['status']) {
            case "added":
                $value = findValue($item['value']);
                return "Property {$path} was added with value: {$value}";
            case "deleted":
                return "Property {$path} was removed";
            case "nested":
                return render($item['children'], $parent, $depth + 1);
            case "changed":
                $value = findValue($item['value']);
                $oldValue = findValue($item['oldValue']);
                return "Property {$path} was updated. From {$oldValue} to {$value}";
            default:
                break;
        }
    }, $diff);
    
    return implode("\n", compact(flattenAll($result)));
}
