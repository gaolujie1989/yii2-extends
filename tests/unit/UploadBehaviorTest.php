<?php

namespace lujie\upload\tests\unit;

use creocoder\flysystem\Filesystem;
use lujie\upload\FileBehavior;
use lujie\upload\models\UploadSavedFile;
use lujie\upload\tests\unit\fixtures\FsFile;
use lujie\upload\UploadBehavior;
use lujie\upload\UploadForm;
use lujie\upload\UploadSavedFileForm;
use Yii;
use yii\di\Instance;
use yii\helpers\VarDumper;
use yii\web\UploadedFile;

class UploadBehaviorTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

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
    public function testUploadForm(): void
    {
        $testFile = __DIR__ . '/fixtures/testUploadedFile.bin';
        $path = __DIR__ . '/fixtures/';

        $uploadForm = new UploadForm([
            'inputName' => 'testFile',
            'path' => $path,
        ]);
        $this->assertFalse($uploadForm->validate());

        $uploadForm = new UploadForm([
            'inputName' => 'testFile',
            'path' => $path,
            'allowedExtensions' => ['bin']
        ]);
        $this->assertTrue($uploadForm->validate(), VarDumper::dumpAsString($uploadForm->getErrors()));

        $this->assertTrue($uploadForm->saveUploadedFile());
        $this->assertFileEquals($testFile,  $path . $uploadForm->file);
    }

    /**
     * @inheritdoc
     */
    public function testUploadSavedFileForm(): void
    {
        /** @var Filesystem $fs */
        $fs = Instance::ensure('filesystem');
        $testFile = __DIR__ . '/fixtures/testUploadedFile.bin';
        $contents = file_get_contents($testFile);

        $uploadForm = new UploadSavedFileForm([
            'model_type' => 'test',
            'inputName' => 'testFile',
            'as upload' => [
                'class' => UploadBehavior::class,
                'attribute' => 'file',
                'inputName' => 'testFile',
                'path' => 'tests',
                'fs' => $fs,
                'newNameTemplate' => 'test/{date}/test_{datetime}_{rand}.{ext}'
            ]
        ]);
        $this->assertFalse($uploadForm->validate());

        $uploadForm = new UploadSavedFileForm([
            'model_type' => 'test',
            'inputName' => 'testFile',
            'allowedExtensions' => ['bin'],
            'as upload' => [
                'class' => UploadBehavior::class,
                'attribute' => 'file',
                'inputName' => 'testFile',
                'path' => 'tests',
                'fs' => $fs,
                'newNameTemplate' => 'test/{date}/test_{datetime}_{rand}.{ext}'
            ],
            'as file' => [
                'class' => FileBehavior::class,
                'attribute' => 'file',
                'path' => 'tests',
                'fs' => $fs,
                'unlinkOnUpdate' => true,
                'unlinkOnDelete' => true,
            ]
        ]);
        $this->assertTrue($uploadForm->validate(), VarDumper::dumpAsString($uploadForm->getErrors()));

        $this->assertTrue($uploadForm->save());
        $this->assertEquals($contents, $uploadForm->getContent());
    }
}
