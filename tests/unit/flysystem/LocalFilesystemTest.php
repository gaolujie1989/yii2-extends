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

class LocalFilesystemTest extends FilesystemTest
{
    /**
     * @return Filesystem
     * @inheritdoc
     */
    protected function getFilesystem(): Filesystem
    {
        return new LocalFilesystem(['path' => '@statics']);
    }
}
