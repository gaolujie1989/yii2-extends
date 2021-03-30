<?php

namespace lujie\data\exchange\tests\unit\file;

use lujie\extend\file\readers\CsvReader;
use lujie\extend\file\writers\CsvWriter;

class CsvExporterTest extends \Codeception\Test\Unit
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
        $file = __DIR__ . '/../fixtures/test_export.csv';
        if (file_exists($file)) {
            unlink($file);
        }
        $exporter = new CsvWriter();

        $dataWithNoKey = [
            ['columnA', 'columnB'],
            ['AAA1', 'BBB1'],
            ['AAA2', 'BBB2'],
            ['AAA3', 'BBB3'],
        ];
        $exporter->keyAsHeader = false;
        $exporter->write($file, $dataWithNoKey);
        $parser = new CsvReader();
        $parser->firstLineIsHeader = false;
        $this->assertEquals($dataWithNoKey, $parser->read($file));

        if (file_exists($file)) {
            unlink($file);
        }
        $dataWithKey = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1'],
            ['columnA' => 'AAA2', 'columnB' => 'BBB2'],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3'],
        ];
        $exporter->keyAsHeader = true;
        $exporter->write($file, $dataWithKey);
        $parser->firstLineIsHeader = true;
        $this->assertEquals($dataWithKey, $parser->read($file));
    }
}
