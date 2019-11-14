<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\file;


use lujie\extend\file\readers\PdfReader;
use lujie\extend\file\readers\TextReader;
use lujie\extend\file\writers\PdfWriter;
use lujie\extend\file\writers\TextWriter;
use Yii;
use yii\helpers\FileHelper;

class PdfReaderWriterTest extends \Codeception\Test\Unit
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

        $file = $path . '/test.pdf';
        $data = [
            'aaa',
            'bbb',
            'ccc',
            'ddd',
        ];
        if (file_exists($file)) {
            unlink($file);
        }
        $writer = new PdfWriter();
        $writer->write($file, $data);

        $reader = new PdfReader();
        $readData = $reader->read($file);
        $this->assertEquals($data, $readData);
    }
}
