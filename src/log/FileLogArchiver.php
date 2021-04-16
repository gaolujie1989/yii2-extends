<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\log;

use creocoder\flysystem\Filesystem;
use yii\base\BaseObject;

/**
 * Class FileLogArchiver
 * @package lujie\extend\log
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileLogArchiver extends BaseObject
{
    /**
     * @var string|array
     */
    public $filesystem;

    /**
     * @return Filesystem
     * @inheritdoc
     */
    public function getFilesystem(): Filesystem
    {

    }
}