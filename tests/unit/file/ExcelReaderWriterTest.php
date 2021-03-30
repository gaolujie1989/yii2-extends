<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\file;

use lujie\extend\file\readers\ExcelReader;
use lujie\extend\file\writers\ExcelWriter;
use lujie\fulfillment\tasks\PullFulfillmentWarehouseStockMovementTask;
use Yii;
use yii\helpers\FileHelper;

class ExcelReaderWriterTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Reader\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $path = Yii::getAlias('@runtime/tests');
        FileHelper::createDirectory($path);

        $file = $path . '/test.xlsx';
        $data = [
            [
                'aaa' => 'a1',
                'bbb' => 'b1',
            ],
            [
                'aaa' => 'a2',
                'bbb' => 'b2',
            ],
        ];
        if (file_exists($file)) {
            unlink($file);
        }
        $writer = new ExcelWriter();
        $writer->adapter = ExcelWriter::ADAPTER_PhpSpreadsheet;
        $writer->write($file, $data);

        $reader = new ExcelReader();
        $readData = $reader->read($file);
        $this->assertEquals($data, $readData);

        $writer = new ExcelWriter();
        $writer->adapter = ExcelWriter::ADAPTER_XLSXWriter;
        $writer->write($file, $data);

        $data = [
            [
                'aaa' => 'a1',
                'bbb' => 'b1',
            ],
            [
                'aaa' => 'a2',
                'bbb' => 'b2',
            ],
        ];
        $reader = new ExcelReader();
        $readData = $reader->read($file);
        $this->assertEquals($data, $readData);
    }

    public function testAbc2int()
    {
        $this->assertEquals(1, ExcelReader::abc2Int('A'));
        $this->assertEquals(10, ExcelReader::abc2Int('J'));
        $this->assertEquals(26, ExcelReader::abc2Int('Z'));
        $this->assertEquals(27, ExcelReader::abc2Int('AA'));
        $this->assertEquals(256, ExcelReader::abc2Int('IV'));
        $this->assertEquals(702, ExcelReader::abc2Int('ZZ'));
        $this->assertEquals(703, ExcelReader::abc2Int('AAA'));
    }
}
