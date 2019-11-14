<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\file;


use lujie\extend\file\readers\TextReader;
use lujie\extend\file\writers\TextWriter;
use Yii;
use yii\helpers\FileHelper;

class TextReaderWriterTest extends \Codeception\Test\Unit
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

        $file = $path . '/test.xlsx';
        $data = [
            'aaa',
            'bbb',
            'ccc',
            'ddd',
        ];
        if (file_exists($file)) {
            unlink($file);
        }
        $writer = new TextWriter();
        $writer->write($file, $data);

        $reader = new TextReader();
        $readData = $reader->read($file);
        $readData = array_map('trim', $readData);
        $this->assertEquals($data, $readData);
    }
}
