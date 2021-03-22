<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\file;

use lujie\extend\file\writers\ZipWriter;
use Yii;
use yii\helpers\FileHelper;

class ZipReaderWriterTest extends \Codeception\Test\Unit
{
    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testMe(): void
    {
        $path = Yii::getAlias('@runtime/tests');
        FileHelper::createDirectory($path);

        $file = $path . '/test.zip';
        $data = [
            __DIR__ . '/ZipReaderWriterTest.php',
            'ZipTest.php' => __DIR__ . '/ZipReaderWriterTest.php',
            'fileTest' => __DIR__
        ];

        if (file_exists($file)) {
            unlink($file);
        }

        $writer = new ZipWriter();
        $writer->write($file, $data);
    }
}
