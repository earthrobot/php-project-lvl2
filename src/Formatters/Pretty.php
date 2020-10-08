<?php

namespace Differ\Formatters\Pretty;

function flattenAll($collection)
{
    $result = [];

    foreach ($collection as $value) {
        if (is_array($value)) {
            $result = array_merge($result, flattenAll($value));
        } else {
            $result[] = $value;
        }
    }

    return $result;
}

function toString($item, $depth = 1)
{
    $result = [];
    $indent = str_repeat("  ", $depth);

    foreach ($item as $k => $v) {
        if (!is_array($v) && !is_object($v)) {
            $result[] = $indent . "  " . $k . ": " . $v;
        } else {
            $result[] = $indent . "  " . $k . ": {";
            $result[] = toString($v, $depth + 2);
            $result[] = $indent . "  }";
        }
    }
    return flattenAll($result);
}

function diffPrint(array $diff, $depth = 1)
{
    $result = [];
    $indent = str_repeat("  ", $depth);
    
    foreach ($diff as $item) {
        if (array_key_exists("value", $item) && is_bool($item['value'])) {
            if ($item['value'] === true) {
                $item['value'] = 'true';
            } elseif ($item['value'] === false) {
                $item['value'] = 'false';
            }
        }
        if (array_key_exists("oldValue", $item) && is_bool($item['oldValue'])) {
            if ($item['oldValue'] === true) {
                $item['oldValue'] = 'true';
            } elseif ($item['oldValue'] === false) {
                $item['oldValue'] = 'false';
            }
        }
        if ($item['status'] == 'added') {
            if (!is_array($item['value']) && !is_object($item['value'])) {
                $result[] = $indent . "+ " . $item['name'] . ": " . $item['value'];
            } else {
                $result[] = $indent . "+ " . $item['name'] . ": {";
                $result[] = toString($item['value'], $depth + 2);
                $result[] = $indent . "  }";
            }
        } elseif ($item['status'] == 'deleted') {
            if (!is_array($item['value']) && !is_object($item['value'])) {
                $result[] = $indent . "- " . $item['name'] . ": " . $item['value'];
            } else {
                $result[] = $indent . "- " . $item['name'] . ": {";
                $result[] = toString($item['value'], $depth + 2);
                $result[] = $indent . "  }";
            }
        } elseif ($item['status'] == 'nested') {
            $result[] = $indent . "  " . $item['name'] . ": {";
            $result[] = diffPrint($item['children'], $depth + 2);
            $result[] = $indent . "  }";
        } elseif ($item['status'] == 'changed') {
            if (!is_array($item['oldValue']) && !is_object($item['oldValue'])) {
                $result[] = $indent . "- " . $item['name'] . ": " . $item['oldValue'];
            } else {
                $result[] = $indent . "- " . $item['name'] . ": {";
                $result[] = toString($item['oldValue'], $depth + 2);
                $result[] = $indent . "  }";
            }
            if (!is_array($item['value']) && !is_object($item['value'])) {
                $result[] = $indent . "+ " . $item['name'] . ": " . $item['value'];
            } else {
                $result[] = $indent . "+ " . $item['name'] . ": {";
                $result[] = toString($item['value'], $depth + 2);
                $result[] = $indent . "  }";
            }
        } elseif ($item['status'] == 'unchanged') {
            $result[] = $indent . "  " . $item['name'] . ": " . $item['value'];
        }
    }
    
    return implode("\n", flattenAll($result));
}
