<?php

namespace Gendiff\tests\GendiffTest;

use PHPUnit\Framework\TestCase;
use function Gendiff\src\Gendiff\genDiff;

class GendiffTest extends TestCase
{
    public function testGendiff()
    {
        $diffPretty = file_get_contents(__DIR__ . '/../tests/fixtures/gendiff_pretty.txt');
        $diffPlain = file_get_contents(__DIR__ . '/../tests/fixtures/gendiff_plain.txt');
        $diffJson = file_get_contents(__DIR__ . '/../tests/fixtures/gendiff_json.txt');

        $this->assertSame($diffPretty, genDiff("newfile1.json", "newfile2.json"));
        $this->assertSame($diffPlain, genDiff("newfile1.json", "newfile2.json", "plain"));
        $this->assertSame($diffJson, genDiff("newfile1.json", "newfile2.json", "json"));

        $this->assertSame($diffPretty, genDiff("newfile1.yml", "newfile2.yml"));
        $this->assertSame($diffPlain, genDiff("newfile1.yml", "newfile2.yml", "plain"));
        $this->assertSame($diffJson, genDiff("newfile1.yml", "newfile2.yml", "json"));
    }
}
