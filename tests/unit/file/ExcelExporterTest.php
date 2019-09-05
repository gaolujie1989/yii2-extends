<?php

namespace lujie\data\exchange\tests\unit\file;

use lujie\data\exchange\file\writers\ExcelWriter;
use lujie\data\exchange\file\readers\ExcelReader;

class ExcelExporterTest extends \Codeception\Test\Unit
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
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $file = __DIR__ . '/../fixtures/test_export.xlsx';
        if (file_exists($file)) {
            unlink($file);
        }
        $exporter = new ExcelWriter();

        $dataWithNoKey = [
            ['columnA', 'columnB'],
            ['AAA1', 'BBB1'],
            ['AAA2', 'BBB2'],
            ['AAA3', 'BBB3'],
        ];
        $exporter->keyAsHeader = false;
        $exporter->write($file, $dataWithNoKey);
        $parser = new ExcelReader();
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
        $parser = new ExcelReader();
        $parser->firstLineIsHeader = true;
        $this->assertEquals($dataWithKey, $parser->read($file));
    }
}
