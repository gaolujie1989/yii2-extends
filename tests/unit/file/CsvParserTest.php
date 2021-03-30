<?php

namespace lujie\data\exchange\tests\unit\file;

use lujie\extend\file\readers\CsvReader;

class CsvParserTest extends \Codeception\Test\Unit
{
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
        $parser = new CsvReader();

        $dataWithNoHeader = [
            ['columnA', 'columnB'],
            ['AAA1', 'BBB1'],
            ['AAA2', 'BBB2'],
            ['AAA3', 'BBB3'],
        ];
        $parser->firstLineIsHeader = false;
        $data = $parser->read($file);
        $this->assertEquals($dataWithNoHeader, $data);

        $dataWithHeader = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1'],
            ['columnA' => 'AAA2', 'columnB' => 'BBB2'],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3'],
        ];
        $parser->firstLineIsHeader = true;
        $data = $parser->read($file);
        $this->assertEquals($dataWithHeader, $data);
    }
}
