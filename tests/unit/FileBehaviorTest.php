<?php

namespace lujie\upload\tests\unit;

use lujie\upload\behaviors\FileBehavior;
use lujie\upload\models\UploadSavedFile;
use Yii;

class FileBehaviorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
        $this->initTestFiles();
    }

    protected function _after()
    {
    }

    // tests
    protected function initTestFiles(): void
    {
        $testFile = __DIR__ . '/fixtures/testUploadedFile.bin';
        $savedFile = __DIR__ . '/fixtures/savedUploadedFile.bin';
        if (file_exists($savedFile)) {
            unlink($savedFile);
        }
        copy($testFile, $savedFile);
    }

    // tests
    public function testMe(): void
    {
        $testFile = __DIR__ . '/fixtures/testUploadedFile.bin';
        $savedFile = __DIR__ . '/fixtures/savedUploadedFile.bin';
        $contents = file_get_contents($testFile);
        $staticUrl = 'xxx.com/';
        Yii::$app->params['staticUrl'] = $staticUrl;
        $file = new UploadSavedFile([
            'file' => 'savedUploadedFile.bin',
            'name' => 'Uploaded Bin File',
            'ext' => 'bin',
            'size' => 123,
            'as file' => [
                'class' => FileBehavior::class,
                'attribute' => 'file',
                'fs' => null,
                'path' => __DIR__ . '/fixtures/',
                'unlinkOnUpdate' => true,
                'unlinkOnDelete' => true,
            ]
        ]);
        $this->assertTrue($file->save());

        $this->assertEquals($staticUrl . 'savedUploadedFile.bin', $file->getUrl());
        $this->assertEquals($savedFile, $file->getPath());
        $this->assertEquals($contents, $file->getContent());

        $file->file = 'xxx.file';
        $file->save();
        $this->assertFileNotExists($savedFile);

        copy($testFile, $savedFile);
        $this->assertFileExists($savedFile);
        $file->file = 'savedUploadedFile.bin';
        $file->delete();
        $this->assertFileNotExists($savedFile);
    }
}
