<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\backup\manager;

use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\di\Instance;

/**
 * Class BackupManagerCommand
 * @package lujie\backup\manager
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BackupManagerCommand extends Controller
{
    /**
     * 'xxx' => [
     *      'database' => 'xx',
     *      'destinations' => [],
     *      'compression' => 'gzip',
     * ]
     * @var array
     */
    public $backup = [];

    public $restore = [];

    /**
     * @var BackupManager
     */
    public $backupManager = 'backupManager';

    public $database = 'db';

    public $destinations = [];

    public $compression = 'gzip';

    public $storage = '';

    public $storagePath = '';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->backupManager = Instance::ensure($this->backupManager, BackupManager::class);
    }

    /**
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @inheritdoc
     */
    public function actionBackup(string $name = ''): void
    {
        if (isset($this->backup[$name])) {
            $backup = $this->backup[$name];
            $this->backupManager->backup($backup['database'], $backup['destinations'], $backup['compression'] ?? $this->compression);
        } else {
            $this->backupManager->backup($this->database, $this->destinations, $this->compression);
        }
    }

    /**
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @inheritdoc
     */
    public function actionRestore(string $name = ''): void
    {
        if (isset($this->restore[$name])) {
            $restore = $this->restore[$name];
            $this->backupManager->restore($restore['storage'], $restore['storagePath'], $restore['database'], $restore['compression'] ?? $this->compression);
        } else {
            $this->backupManager->restore($this->storage, $this->storagePath, $this->database, $this->compression);
        }
    }
}
