<?php

namespace Differ\GendiffTest;

use PHPUnit\Framework\TestCase;
use function Differ\Differ\genDiff;

function getPath($fixtureName)
{
    $fixturePath = realpath(__DIR__ . '/fixtures/' . $fixtureName);
    return $fixturePath;
}

class GendiffTest extends TestCase
{
    public function testGendiff()
    {
        $diffPretty = getPath("gendiff_pretty.txt");
        $diffPlain = getPath("gendiff_plain.txt");
        $diffJson = getPath("gendiff_json.txt");

        $filePathJson1 = getPath("newfile1.json");
        $filePathJson2 = getPath("newfile2.json");

        $this->assertStringEqualsFile($diffPretty, genDiff($filePathJson1, $filePathJson2));
        $this->assertStringEqualsFile($diffPlain, genDiff($filePathJson1, $filePathJson2, "plain"));
        $this->assertStringEqualsFile($diffJson, genDiff($filePathJson1, $filePathJson2, "json"));

        $filePathYml1 = getPath("newfile1.yml");
        $filePathYml2 = getPath("newfile2.yml");

        $this->assertStringEqualsFile($diffPretty, genDiff($filePathYml1, $filePathYml2));
        $this->assertStringEqualsFile($diffPlain, genDiff($filePathYml1, $filePathYml2, "plain"));
        $this->assertStringEqualsFile($diffJson, genDiff($filePathYml1, $filePathYml2, "json"));
    }
}
