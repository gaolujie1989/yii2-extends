<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\file;

use lujie\extend\file\readers\CompressReader;
use lujie\extend\file\writers\CompressWriter;
use Yii;
use yii\helpers\FileHelper;

class CompressReaderWriterTest extends \Codeception\Test\Unit
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
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $path = Yii::getAlias('@runtime/tests');
        FileHelper::createDirectory($path);

        $file = $path . '/test.bin';
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
        $writer = new CompressWriter();
        $writer->write($file, $data);

        $reader = new CompressReader();
        $readData = $reader->read($file);
        $this->assertEquals($data, $readData);
    }
}
