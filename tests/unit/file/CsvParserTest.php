<?php

namespace lujie\data\exchange\tests\unit\file;

use lujie\data\exchange\file\parsers\CsvParser;

class CsvParserTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @inheritdoc
     */
    public function testMe(): void
    {
        $file = __DIR__ . '/../fixtures/test.csv';
        $parser = new CsvParser();

        $dataWithNoHeader = [
            ['columnA', 'columnB'],
            ['AAA1', 'BBB1'],
            ['AAA2', 'BBB2'],
            ['AAA3', 'BBB3'],
        ];
        $parser->firstLineIsHeader = false;
        $data = $parser->parseFile($file);
        $this->assertEquals($dataWithNoHeader, $data);

        $dataWithHeader = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1'],
            ['columnA' => 'AAA2', 'columnB' => 'BBB2'],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3'],
        ];
        $parser->firstLineIsHeader = true;
        $data = $parser->parseFile($file);
        $this->assertEquals($dataWithHeader, $data);
    }
}
