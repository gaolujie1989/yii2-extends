<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\backup\manager;

use lujie\executing\ExecutableInterface;
use lujie\executing\ExecutableTrait;
use yii\base\BaseObject;
use yii\di\Instance;

/**
 * Class BackupExecutable
 * @package lujie\backup\manager
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BackupExecutable extends BaseObject implements ExecutableInterface
{
    use ExecutableTrait;

    /**
     * @var BackupManager
     */
    public $backupManager = 'backupManager';

    /**
     * @var string
     */
    public $backupName;

    /**
     * @return mixed|void
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function execute()
    {
        $this->backupManager = Instance::ensure($this->backupManager, BackupManager::class);
        $this->backupManager->runBackup($this->backupName);
    }
}
