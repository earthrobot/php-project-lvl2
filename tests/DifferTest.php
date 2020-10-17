<?php

namespace Differ\DifferTest;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

function getPath($fixtureName)
{
    $parts = [__DIR__, 'fixtures', $fixtureName];
    $fixturePath = implode("/", $parts);
    return $fixturePath;
}

class DifferTest extends TestCase
{
    /**
     * @dataProvider additionProvider
     */

    public function testGendiff($expected, $filePath1, $filePath2, $format)
    {
        $this->assertStringEqualsFile($expected, genDiff(getPath($filePath1), getPath($filePath2), $format));

    }

    public function additionProvider()
    {
        $diffPlain = getPath("gendiff_plain.txt");
        $diffJson = getPath("gendiff_json.txt");

        return [
            'PlainJson' => [$diffPlain, "newfile1.json", "newfile2.json", "plain"],
            'JsonJson' => [$diffJson, "newfile1.json", "newfile2.json", "json"],
            'PlainYML' => [$diffPlain, "newfile1.yml", "newfile2.yml", "plain"],
            'JsonYML' => [$diffJson, "newfile1.yml", "newfile2.yml", "json"]
        ];
    }

    public function testGendiffWithoutFormat()
    {
        $this->assertStringEqualsFile(getPath("gendiff_pretty.txt"), genDiff(getPath("newfile1.json"), getPath("newfile2.yml")));

    }
}
