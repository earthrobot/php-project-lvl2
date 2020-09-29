<?php

use PHPUnit\Framework\TestCase;
use function Gendiff\src\Gendiff\gendiff;
use function Gendiff\src\Parsers\parse;
use function Gendiff\src\Formatters\diffPrint;
use function Gendiff\src\Formatters\diffDescribe;
use function Gendiff\src\Formatters\diffJson;

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
        $json1 = file_get_contents(__DIR__ . '/features/newfile1.json');
        $json2 = file_get_contents(__DIR__ . '/features/newfile2.json');
        $diff = '{"common":{"name":"common","status":"nested","children":{"follow":{"name":"follow","status":"added","value":false},"setting1":{"name":"setting1","status":"unchanged","value":"Value 1"},"setting2":{"name":"setting2","status":"deleted","value":200},"setting3":{"name":"setting3","status":"changed","value":{"key":"value"},"oldValue":true},"setting4":{"name":"setting4","status":"added","value":"blah blah"},"setting5":{"name":"setting5","status":"added","value":{"key5":"value5"}},"setting6":{"name":"setting6","status":"nested","children":{"doge":{"name":"doge","status":"nested","children":{"wow":{"name":"wow","status":"changed","value":"so much","oldValue":"too much"}}},"key":{"name":"key","status":"unchanged","value":"value"},"ops":{"name":"ops","status":"added","value":"vops"}}}}},"group1":{"name":"group1","status":"nested","children":{"baz":{"name":"baz","status":"changed","value":"bars","oldValue":"bas"},"foo":{"name":"foo","status":"unchanged","value":"bar"},"nest":{"name":"nest","status":"changed","value":"str","oldValue":{"key":"value"}}}},"group2":{"name":"group2","status":"deleted","value":{"abc":12345,"deep":{"id":45}}},"group3":{"name":"group3","status":"added","value":{"fee":100500,"deep":{"id":{"number":45}}}}}';            
        $parsedFile1 = parse($json1);
        $parsedFile2 = parse($json2);

        $this->assertSame($diff, diffJson(gendiff($parsedFile1, $parsedFile2)));
    }
}
