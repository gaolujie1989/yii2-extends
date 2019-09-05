<?php

namespace lujie\data\exchange\tests\unit\file;

use lujie\data\exchange\file\writers\CsvWriter;
use lujie\data\exchange\file\readers\CsvReader;

class CsvExporterTest extends \Codeception\Test\Unit
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
        $exporter->exportToFile($file, $dataWithNoKey);
        $parser = new CsvReader();
        $parser->firstLineIsHeader = false;
        $this->assertEquals($dataWithNoKey, $parser->parseFile($file));

        if (file_exists($file)) {
            unlink($file);
        }
        $dataWithKey = [
            ['columnA' => 'AAA1', 'columnB' => 'BBB1'],
            ['columnA' => 'AAA2', 'columnB' => 'BBB2'],
            ['columnA' => 'AAA3', 'columnB' => 'BBB3'],
        ];
        $exporter->keyAsHeader = true;
        $exporter->exportToFile($file, $dataWithKey);
        $parser->firstLineIsHeader = true;
        $this->assertEquals($dataWithKey, $parser->parseFile($file));
    }
}
