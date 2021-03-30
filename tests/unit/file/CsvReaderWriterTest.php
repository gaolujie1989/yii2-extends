<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\file;

use lujie\extend\file\readers\CsvReader;
use lujie\extend\file\writers\CsvWriter;
use Yii;
use yii\helpers\FileHelper;

class CsvReaderWriterTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
    }

    protected function _after()
    {
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $path = Yii::getAlias('@runtime/tests');
        FileHelper::createDirectory($path);

        $file = $path . '/test.csv';
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
        $writer = new CsvWriter();
        $writer->write($file, $data);

        $reader = new CsvReader();
        $readData = $reader->read($file);
        $this->assertEquals($data, $readData);

        if (file_exists($file)) {
            unlink($file);
        }
        $writer = new CsvWriter();
        $writer->write($file, $data);

        $reader = new CsvReader();
        $reader->flag = false;
        $readData = $reader->read($file);
        $this->assertEquals($data, $readData);
    }
}
