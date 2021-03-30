<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\flysystem\tests\unit;

use creocoder\flysystem\Filesystem;
use creocoder\flysystem\LocalFilesystem;
use lujie\flysystem\CombinedFilesystem;
use Yii;
use yii\di\Instance;

class CombinedFilesystemTest extends \Codeception\Test\Unit
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
        /** @var Filesystem $fs1 */
        $fs1 = Instance::ensure([
            'class' => LocalFilesystem::class,
            'path' => Yii::getAlias('@runtime/fs1')
        ]);
        /** @var Filesystem $fs2 */
        $fs2 = Instance::ensure([
            'class' => LocalFilesystem::class,
            'path' => Yii::getAlias('@runtime/fs2')
        ]);
        $filesystem = new CombinedFilesystem([
            'filesystems' => [
                'fs1' => $fs1,
                'fs2' => $fs2,
            ]
        ]);

        $file1 = 'fs1/file1.txt';
        $content1 = 'file1Content';
        $file2 = 'fs2/file2.txt';
        $content2 = 'file2Content';
        $file3 = 'fs3/file3';
        $content3 = 'file3Content';

        foreach ([$file1, $file2, $file3] as $file) {
            foreach ([$fs1, $fs2] as $fs) {
                if ($fs->has($file)) {
                    $fs->delete($file);
                }
            }
        }

        $this->assertTrue($filesystem->write($file1, $content1));
        $this->assertTrue($filesystem->write($file2, $content2));
        $this->assertTrue($filesystem->write($file3, $content3));

        $this->assertEquals($content1, $filesystem->read($file1));
        $this->assertEquals($content2, $filesystem->read($file2));
        $this->assertEquals($content1, $fs1->read($file1));
        $this->assertEquals($content2, $fs2->read($file2));

        $this->assertFalse($filesystem->has($file3));
        $this->assertFalse($fs1->has($file2));
        $this->assertFalse($fs2->has($file1));
        $this->assertFalse($fs1->has($file3));
        $this->assertFalse($fs2->has($file3));

        $this->assertTrue($filesystem->delete($file1));
        $this->assertTrue($filesystem->delete($file2));
    }
}
