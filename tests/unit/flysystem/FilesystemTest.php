<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\flysystem;

use Codeception\Test\Unit;
use lujie\extend\flysystem\Filesystem;
use lujie\extend\flysystem\LocalFilesystem;
use lujie\flysystem\QCloudCosFilesystem;
use yii\di\Instance;

abstract class FilesystemTest extends Unit
{
    /**
     * @return Filesystem
     * @inheritdoc
     */
    abstract protected function getFilesystem(): Filesystem;

    /**
     * @throws \Throwable
     * @inheritdoc
     */
    public function testReadWriteDelete(): void
    {
        $filesystem = $this->getFilesystem();

        $path = 'test/123456789.bin';
        $contents = '1234567890123ABC';
        $this->assertFalse($filesystem->fileExists($path));
        $filesystem->write($path, $contents);
        $this->assertTrue($filesystem->fileExists($path));
        $this->assertEquals($contents, $filesystem->read($path));

        $anotherContents = 'BCD1234567890123';
        $filesystem->write($path, $anotherContents);
        $this->assertTrue($filesystem->fileExists($path));
        $this->assertEquals($anotherContents, $filesystem->read($path));

        $filesystem->delete($path);
        $this->assertFalse($filesystem->fileExists($path));
    }
}
