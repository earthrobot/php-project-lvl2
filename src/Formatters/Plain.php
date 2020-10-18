<?php

namespace Differ\Formatters\Plain;

use function Funct\Collection\flattenAll;
use function Funct\Collection\compact;

function stringify($value)
{
    if (is_bool($value)) {
        return $value ? 'true' : 'false';
    }

    if (is_null($value)) {
        return 'null';
    }

    if (is_int($value)) {
        return (string) $value;
    }

    if (is_string($value)) {
        return "'{$value}'";
    }

    return "[complex value]";
}

function buildPrint(array $diff, $ancestorPath = "")
{
    $filteredDiff = array_filter($diff, function ($item) {
        return $item['status'] != "unchanged";
    });

    $result = array_map(function ($item) use ($ancestorPath) {

        $path = ($ancestorPath == "") ? $item['key'] : "{$ancestorPath}.{$item['key']}";
        
        switch ($item['status']) {
            case "added":
                $value = stringify($item['value']);
                return "Property '{$path}' was added with value: {$value}";
            case "deleted":
                return "Property '{$path}' was removed";
            case "nested":
                return buildPrint($item['children'], $path);
            case "changed":
                $value = stringify($item['value']);
                $oldValue = stringify($item['oldValue']);
                return "Property '{$path}' was updated. From {$oldValue} to {$value}";
            case "unchanged":
                break;
            default:
                throw new \Exception("Unknown item status: {$item['status']}");
        }
    }, $filteredDiff);
    
    return implode("\n", compact(flattenAll($result)));
}

function render(array $diff)
{
    return buildPrint($diff);
}
