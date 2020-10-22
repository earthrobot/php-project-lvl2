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
     * @dataProvider casesProvider
     */

    public function testGendiff($expected, $filePath1, $filePath2, $format)
    {
        $this->assertStringEqualsFile($expected, genDiff(getPath($filePath1), getPath($filePath2), $format));

    }

    public function casesProvider()
    {
        $diffPlainPath = getPath("gendiff_plain.txt");
        $diffJsonPath = getPath("gendiff_json.txt");
        $diffPrettyPath = getPath("gendiff_pretty.txt");

        return [
            'PrettyJson' => [$diffPrettyPath, "file1.json", "file2.json", "pretty"],
            'PlainJson' => [$diffPlainPath, "file1.json", "file2.json", "plain"],
            'JsonJson' => [$diffJsonPath, "file1.json", "file2.json", "json"],
            'PrettyYML' => [$diffPrettyPath, "file1.yml", "file2.yml", "pretty"],
            'PlainYML' => [$diffPlainPath, "file1.yml", "file2.yml", "plain"],
            'JsonYML' => [$diffJsonPath, "file1.yml", "file2.yml", "json"]
        ];
    }

    public function testGendiffWithoutFormat()
    {
        $this->assertStringEqualsFile(getPath("gendiff_pretty.txt"), genDiff(getPath("file1.json"), getPath("file2.yml")));

    }
}
