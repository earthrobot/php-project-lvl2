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

function buildPlain(array $diff, $ancestors = "")
{
    $filteredDiff = array_filter($diff, fn($item) => $item['type'] != "unchanged");

    $result = array_map(function ($item) use ($ancestors) {

        $property = ($ancestors == "") ? $item['key'] : "{$ancestors}.{$item['key']}";
        
        switch ($item['type']) {
            case "added":
                $value = stringify($item['value']);
                return "Property '{$property}' was added with value: {$value}";
            case "deleted":
                return "Property '{$property}' was removed";
            case "nested":
                return buildPlain($item['children'], $property);
            case "changed":
                $value = stringify($item['value']);
                $oldValue = stringify($item['oldValue']);
                return "Property '{$property}' was updated. From {$oldValue} to {$value}";
            default:
                throw new \Exception("Unknown item type: {$item['type']}");
        }
    }, $filteredDiff);
    
    return implode("\n", flattenAll($result));
}

function render(array $diff)
{
    return buildPlain($diff);
}
