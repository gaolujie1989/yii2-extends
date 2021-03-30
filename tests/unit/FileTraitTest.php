<?php

namespace lujie\upload\tests\unit;

use creocoder\flysystem\Filesystem;
use lujie\upload\tests\unit\fixtures\FsFile;
use yii\di\Instance;
use yii\web\UploadedFile;

class FileTraitTest extends \Codeception\Test\Unit
{


    protected function _before()
    {
        $this->initTestFiles();
        $this->initPostFiles();
    }

    protected function _after()
    {
    }

    protected function initPostFiles(): void
    {
        $file = __DIR__ . '/fixtures/tempUploadedFile.bin';
        $_FILES = [
            'testFile' => [
                'name' => 'test uploaded bin file.bin',
                'tmp_name' => $file,
                'type' => 'application/bin',
                'size' => filesize($file),
                'error' => UPLOAD_ERR_OK,
            ],
            'partialFile' => [
                'name' => 'test uploaded bin file.bin',
                'tmp_name' => $file,
                'type' => 'application/bin',
                'size' => 36,
                'error' => UPLOAD_ERR_PARTIAL,
            ]
        ];
    }

    // tests
    protected function initTestFiles(): void
    {
        $testFile = __DIR__ . '/fixtures/testUploadedFile.bin';
        $tempFile = __DIR__ . '/fixtures/tempUploadedFile.bin';
        $saveFile = __DIR__ . '/fixtures/saveUploadedFile.bin';
        if (!file_exists($tempFile)) {
            copy($testFile, $tempFile);
        }
        if (file_exists($saveFile)) {
            unlink($saveFile);
        }
        /** @var Filesystem $fs */
        $fs = Instance::ensure('filesystem');
        $filePath = 'tests/saveUploadedFile.bin';
        if ($fs->has($filePath)) {
            $fs->delete($filePath);
        }
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testLocalFile(): void
    {
        $localFile = new FsFile([
            'fs' => null,
            'path' => __DIR__ . '/fixtures',
        ]);
        $fileName = 'saveUploadedFile.bin';
        $saveFile = __DIR__ . '/fixtures/saveUploadedFile.bin';
        $testFile = __DIR__ . '/fixtures/testUploadedFile.bin';
        $tempFile = __DIR__ . '/fixtures/tempUploadedFile.bin';

        $this->assertTrue($localFile->saveFile($fileName, $tempFile));
        $this->assertFileExists($saveFile);
        $this->assertFileExists($tempFile);
        $this->assertFileEquals($testFile, $saveFile);

        $localFile->deleteFile($fileName);
        $this->assertFileNotExists($saveFile);

        $this->assertTrue($localFile->saveFile($fileName, $tempFile, true));
        $this->assertFileExists($saveFile);
        $this->assertFileNotExists($tempFile);
        $this->assertFileEquals($testFile, $saveFile);
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testLocalUploadedFile(): void
    {
        $localFile = new FsFile([
            'fs' => null,
            'path' => __DIR__ . '/fixtures',
        ]);
        $fileName = 'saveUploadedFile.bin';
        $saveFile = __DIR__ . '/fixtures/saveUploadedFile.bin';
        $testFile = __DIR__ . '/fixtures/testUploadedFile.bin';
        $tempFile = __DIR__ . '/fixtures/tempUploadedFile.bin';
        $uploadedFile = UploadedFile::getInstanceByName('testFile');

        $this->assertTrue($localFile->saveUploadedFile($fileName, $uploadedFile, false));
        $this->assertFileExists($saveFile);
        $this->assertFileExists($tempFile);
        $this->assertFileEquals($testFile, $saveFile);

        $localFile->deleteFile($fileName);
        $this->assertFileNotExists($saveFile);

        $this->assertTrue($localFile->saveUploadedFile($fileName, $uploadedFile));
        $this->assertFileExists($saveFile);
        $this->assertFileNotExists($tempFile);
        $this->assertFileEquals($testFile, $saveFile);
    }

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function testFsFile(): void
    {
        /** @var Filesystem $fs */
        $fs = Instance::ensure('filesystem');
        $localFile = new FsFile([
            'fs' => $fs,
            'path' => 'tests',
        ]);
        $fileName = 'saveUploadedFile.bin';
        $filePath = 'tests/saveUploadedFile.bin';
        $testFile = __DIR__ . '/fixtures/testUploadedFile.bin';
        $tempFile = __DIR__ . '/fixtures/tempUploadedFile.bin';
        $contents = file_get_contents($testFile);

        $this->assertTrue($localFile->saveFile($fileName, $tempFile));
        $this->assertTrue($fs->has($filePath));
        $this->assertFileExists($tempFile);
        $this->assertEquals($contents, $fs->read($filePath));

        $localFile->deleteFile($fileName);
        $this->assertFalse($fs->has($filePath));

        $this->assertTrue($localFile->saveFile($fileName, $tempFile, true));
        $this->assertTrue($fs->has($filePath));
        $this->assertFileNotExists($tempFile);
        $this->assertEquals($contents, $fs->read($filePath));
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function testFsUploadedFile(): void
    {
        /** @var Filesystem $fs */
        $fs = Instance::ensure('filesystem');
        $localFile = new FsFile([
            'fs' => $fs,
            'path' => 'tests',
        ]);
        $fileName = 'saveUploadedFile.bin';
        $filePath = 'tests/saveUploadedFile.bin';
        $testFile = __DIR__ . '/fixtures/testUploadedFile.bin';
        $tempFile = __DIR__ . '/fixtures/tempUploadedFile.bin';
        $contents = file_get_contents($testFile);
        $uploadedFile = UploadedFile::getInstanceByName('testFile');

        $this->assertTrue($localFile->saveUploadedFile($fileName, $uploadedFile, false));
        $this->assertTrue($fs->has($filePath));
        $this->assertFileExists($tempFile);
        $this->assertEquals($contents, $fs->read($filePath));

        $localFile->deleteFile($fileName);
        $this->assertFalse($fs->has($filePath));

        $this->assertTrue($localFile->saveUploadedFile($fileName, $uploadedFile));
        $this->assertTrue($fs->has($filePath));
        $this->assertFileNotExists($tempFile);
        $this->assertEquals($contents, $fs->read($filePath));
    }
}
