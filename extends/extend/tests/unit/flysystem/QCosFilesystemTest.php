<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\flysystem;

use lujie\extend\flysystem\Filesystem;
use lujie\extend\flysystem\QCosFilesystem;
use yii\di\Instance;

/**
 * Class QCosFilesystemTest
 * @package lujie\extend\tests\unit\flysystem
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QCosFilesystemTest extends FilesystemTest
{
    /**
     * @return Filesystem
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function getFilesystem(): Filesystem
    {
        /** @var QCosFilesystem $cosFilesystem */
        $cosFilesystem = Instance::ensure('qCloudCos', QCosFilesystem::class);
        return $cosFilesystem;
    }
}
