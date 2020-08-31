<?php

use PHPUnit\Framework\TestCase;
use function Gendiff\src\Gendiff\gendiff;

$autoloadPath1 = __DIR__ . '/../../autoload.php';
$autoloadPath2 = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath1)) {
    require_once $autoloadPath1;
} else {
    require_once $autoloadPath2;
}

class GendiffTest extends TestCase
{
    public function testGendiff()
    {
        $json1 = file_get_contents(__DIR__ . '/features/file1.json');
        $json2 = file_get_contents(__DIR__ . '/features/file2.json');
        $diff = "{
- follow: 
host: hexlet.io
+ verbose: 1
- proxy: 123.234.53.22
- timeout: 50
+ timeout: 20
}";            
        $this->assertSame($diff, gendiff($json1, $json2));
    }
}
