<?php

use PHPUnit\Framework\TestCase;
use function Gendiff\src\Gendiff\gendiff;
use function Gendiff\src\Parsers\parse;
use function Gendiff\src\Formatters\diffPrint;
use function Gendiff\src\Formatters\diffDescribe;

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
        $diff = "Property 'common.follow' was added with value: false
Property 'common.setting2' was removed
Property 'common.setting3' was updated. From true to [complex value]
Property 'common.setting4' was added with value: 'blah blah'
Property 'common.setting5' was added with value: [complex value]
Property 'common.setting6.doge.wow' was updated. From 'too much' to 'so much'
Property 'common.setting6.ops' was added with value: 'vops'
Property 'group1.baz' was updated. From 'bas' to 'bars'
Property 'group1.nest' was updated. From [complex value] to 'str'
Property 'group2' was removed
Property 'group3' was added with value: [complex value]";            
        $parsedFile1 = parse($json1);
        $parsedFile2 = parse($json2);

        $this->assertSame($diff, diffDescribe(gendiff($parsedFile1, $parsedFile2)));
    }
}
