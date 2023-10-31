<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\db;

use lujie\common\history\models\ModelHistoryRelationTrait;
use lujie\extend\flysystem\Filesystem;
use lujie\extend\helpers\ModelHelper;
use yii\di\Instance;

/**
 * Trait ModelFileDeleteTrait
 *
 * @property Filesystem|string $fs
 *
 * @package lujie\extend\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ModelFileDeleteTrait
{
    /**
     * @var string
     */
    public $fs = 'filesystem';

    /**
     * @return string[]
     * @inheritdoc
     */
    public function getFileAttributes(): array
    {
        return ModelHelper::filterAttributes($this->attributes(), ['file']);
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function afterDelete(): void
    {
        parent::afterDelete();
        $this->deleteModelFiles();
    }

    /**
     * @throws \League\Flysystem\FilesystemException
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function deleteModelFiles(): void
    {
        $files = array_filter($this->getAttributes($this->getFileAttributes()));
        if (empty($files)) {
            return;
        }
        /** @var Filesystem $fs */
        $fs = Instance::ensure($this->fs ?? 'filesystem', Filesystem::class);
        foreach ($files as $file) {
            if ($fs->has($file)) {
                $fs->delete($file);
            }
        }
    }
}
