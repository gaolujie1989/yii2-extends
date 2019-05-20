<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\tests\unit\fixtures;

use lujie\upload\FileTrait;
use yii\base\BaseObject;

/**
 * Class FsFile
 * @package lujie\upload\tests\unit\fixtures
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FsFile extends BaseObject
{
    use FileTrait;

    public $fs;

    public $path = '@uploads';

    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initFsAndPath();
    }
}
