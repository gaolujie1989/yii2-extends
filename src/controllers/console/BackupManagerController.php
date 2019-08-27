<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\backup\manager\controllers\console;

use BackupManager\Filesystems\Destination;
use lujie\backup\manager\BackupManager;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\di\Instance;

/**
 * Class BackupManagerCommand
 * @package lujie\backup\manager
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BackupManagerController extends Controller
{
    /**
     * @var BackupManager
     */
    public $backupManager = 'backupManager';

    /**
     * @var string
     */
    public $database = 'db';

    /**
     * @var string[] like ['name:path', 'local:xxxbackup/xxx.sql']
     */
    public $destinations = [];

    /**
     * @var string
     */
    public $compression = 'gzip';

    /**
     * @var string
     */
    public $sourceType = '';

    /**
     * @var string
     */
    public $sourcePath = '';

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
     * @param string $name
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @inheritdoc
     */
    public function actionBackup(string $name = ''): void
    {
        if ($name) {
            $this->backupManager->runBackup($name);
        } else {
            if (empty($this->destinations)) {
                throw new InvalidArgumentException('Destinations must be set');
            }
            $this->backupManager->backup(
                $this->database,
                $this->destinations,
                $this->compression
            );
        }
    }

    /**
     * @param string $name
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @inheritdoc
     */
    public function actionRestore(string $name = ''): void
    {
        if ($name) {
            $this->backupManager->runRestore($name);
        } else {
            if (empty($this->sourceType) || empty($this->sourcePath)) {
                throw new InvalidArgumentException('Source type and path must be set');
            }
            $this->backupManager->restore(
                $this->sourceType,
                $this->sourcePath,
                $this->database,
                $this->compression
            );
        }
    }
}
