<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\flysystem\tests\unit;

use lujie\flysystem\AliyunOssFilesystem;
use yii\di\Instance;

class AliyunOssFilesystemTest extends \Codeception\Test\Unit
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
     * @throws \Throwable
     * @inheritdoc
     */
    public function testMe(): void
    {
        $file = __DIR__ . '/fixtrues/testdata.bin';
        $contents = file_get_contents($file);
        $path = 'pm/27552/525354/testdata_525354.bin';

        /** @var AliyunOssFilesystem $filesystem */
        $filesystem = Instance::ensure('aliyunOss', AliyunOssFilesystem::class);
        $this->assertTrue($filesystem->write($path, $contents));
        $readContent = $filesystem->read($path);
        $this->assertEquals($contents, $readContent);
        $this->assertTrue($filesystem->delete($path));
    }
}
