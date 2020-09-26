<?php

use PHPUnit\Framework\TestCase;
use function Gendiff\src\Gendiff\gendiff;
use function Gendiff\src\Parsers\parse;
use function Gendiff\src\Printers\diffPrint;

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
        $diff = "{
    common: {
      + follow: false
        setting1: Value 1
      - setting2: 200
      - setting3: true
      + setting3: {
            key: value
        }
      + setting4: blah blah
      + setting5: {
            key5: value5
        }
        setting6: {
            doge: {
              - wow: too much
              + wow: so much
            }
            key: value
          + ops: vops
        }
    }
    group1: {
      - baz: bas
      + baz: bars
        foo: bar
      - nest: {
            key: value
        }
      + nest: str
    }
  - group2: {
        abc: 12345
        deep: {
            id: 45
        }
    }
  + group3: {
        fee: 100500
        deep: {
            id: {
                number: 45
            }
        }
    }
}";            
        $parsedFile1 = parse($json1);
        $parsedFile2 = parse($json2);

        $this->assertSame($diff, "{\n" . diffPrint(gendiff($parsedFile1, $parsedFile2)) . "\n}");
    }
}
