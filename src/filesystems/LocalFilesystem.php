<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\backup\manager\filesystems;

use BackupManager\Filesystems\Filesystem as BackupManagerFilesystem;
use League\Flysystem\Filesystem as LeagueFilesystem;
use League\Flysystem\Local\LocalFilesystemAdapter;

/**
 * Class LocalFilesystem
 * @package lujie\backup\manager\filesystems
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class LocalFilesystem implements BackupManagerFilesystem
{
    /**
     * @param $type
     * @return bool
     * @inheritdoc
     */
    public function handles($type): bool
    {
        return strtolower($type ?? '') === 'local';
    }

    /**
     * @param array $config
     * @return LeagueFilesystem
     * @inheritdoc
     */
    public function get(array $config): LeagueFilesystem
    {
        return new LeagueFilesystem(new LocalFilesystemAdapter($config['root']));
    }
}
