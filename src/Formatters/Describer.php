<?php

namespace Gendiff\src\Formatters;

function diffDescribe(array $diff, $parent = [], $depth = 0)
{
    $result = [];
    $path = "";
    
    foreach ($diff as $item) {
        if (array_key_exists("value", $item)) {
            if (is_bool($item['value']) && $item['value'] === true) {
                $value = 'true';  
            } elseif (is_bool($item['value']) && $item['value'] === false) {
                $value = 'false';  
            } elseif (is_array($item['value'])) {
                $value = "[complex value]";
            } else {
                $value = "'" . $item['value'] . "'"; 
            }
        }
        if(array_key_exists("oldValue", $item)) {
            if (is_bool($item['oldValue']) && $item['oldValue'] === true) {
                $oldValue = 'true';  
            } elseif (is_bool($item['oldValue']) && $item['oldValue'] === false) {
                $oldValue = 'false';  
            } elseif (is_array($item['oldValue'])) {
                $oldValue = "[complex value]";
            } else {
                $oldValue = "'" . $item['oldValue'] . "'"; 
            }
        }
        $parent[$depth] = $item['name'];
        if ($depth > 0) {
            $path = "'" . implode(".", $parent) . "'"; 
        } else {
            $path = "'" . $item['name'] . "'"; 
        }
        if ($item['status'] == 'added') {
            $result[] = "Property " . $path . " was added with value: " . $value;
        } elseif ($item['status'] == 'deleted') {
            $result[] = "Property " . $path . " was removed";
        } elseif ($item['status'] == 'nested') {
            $depthChild = $depth + 1;
            $result[] = diffDescribe($item['children'], $parent, $depthChild);
        } elseif ($item['status'] == 'changed') {            
            $result[] = "Property " . $path . " was updated. From " . $oldValue . " to " . $value;
        }
    }
    
    return implode("\n", flattenAll($result));    
}
