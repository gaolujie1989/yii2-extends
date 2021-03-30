<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\flysystem\tests\unit;

use lujie\flysystem\QCloudCosFilesystem;
use yii\di\Instance;

class QCloudCosFilesystemTest extends \Codeception\Test\Unit
{
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
        $path = 'test/testdata_525354.bin';

        /** @var QCloudCosFilesystem $filesystem */
        $filesystem = Instance::ensure('qCloudCos', QCloudCosFilesystem::class);
        $this->assertTrue($filesystem->write($path, $contents));
        $readContent = $filesystem->read($path);
        $this->assertEquals($contents, $readContent);
        $this->assertTrue($filesystem->delete($path));
    }
}
