<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\backup\manager\filesystems;

use BackupManager\Filesystems\Filesystem as BackupManagerFilesystem;
use League\Flysystem\Filesystem as LeagueFilesystem;
use lujie\extend\flysystem\Filesystem;
use yii\di\Instance;

/**
 * Class YiiComponentFilesystem
 * @package lujie\backup\manager\Filesystems
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class YiiComponentFilesystem implements BackupManagerFilesystem
{
    public $type = 'component';

    /**
     * @param $type
     * @return bool
     * @inheritdoc
     */
    public function handles($type): bool
    {
        return strtolower($type) === $this->type;
    }

    /**
     * @param array $config
     * @return LeagueFilesystem
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function get(array $config): LeagueFilesystem
    {
        $component = $config['component'] ?? null;
        /** @var Filesystem $filesystem */
        $filesystem = Instance::ensure($component, Filesystem::class);
        return $filesystem->getFilesystem();
    }
}
