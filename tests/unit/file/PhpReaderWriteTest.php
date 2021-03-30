<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\file;

use lujie\extend\file\readers\PhpReader;
use lujie\extend\file\writers\PhpWriter;
use Yii;
use yii\helpers\FileHelper;

class PhpReaderWriteTest extends \Codeception\Test\Unit
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

        $file = $path . '/test.php';
        $data = [
            'aaa',
            'bbb',
            'ccc',
            'ddd',
        ];
        if (file_exists($file)) {
            unlink($file);
        }
        $writer = new PhpWriter();
        $writer->write($file, $data);

        $reader = new PhpReader();
        $readData = $reader->read($file);
        $readData = array_map('trim', $readData);
        $this->assertEquals($data, $readData);
    }
}
