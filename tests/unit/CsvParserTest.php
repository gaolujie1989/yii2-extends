<?php

namespace lujie\data\exchange\tests\unit;

use lujie\data\exchange\parsers\CsvParser;

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
        $parser = new CsvParser();

        $dataWithNoHeader = [
            ['columnA', 'columnB'],
            ['AAA1', 'BBB1'],
            ['AAA2', 'BBB2'],
            ['AAA3', 'BBB3'],
        ];
        $parser->firstLineIsHeader = false;
        $data = $parser->parse(__DIR__ . '/fixtures/test.csv');
        $this->assertEquals($dataWithNoHeader, $data);

        $dataWithHeader = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1'],
            ['columnA' => 'AAA2', 'columnB' => 'BBB2'],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3'],
        ];
        $parser->firstLineIsHeader = true;
        $data = $parser->parse(__DIR__ . '/fixtures/test.csv');
        $this->assertEquals($dataWithHeader, $data);
    }
}
