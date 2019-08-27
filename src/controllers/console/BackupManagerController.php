<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\backup\manager\controllers\console;

use BackupManager\Filesystems\Destination;
use lujie\backup\manager\BackupManager;
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
     * 'xxx' => [
     *      'database' => 'xx',
     *      'destinations' => [],
     *      'compression' => 'gzip',
     * ]
     * @var array
     */
    public $backup = [];

    /**
     * 'xxx' => [
     *      'storage' => 'xx',
     *      'storagePath' => 'xx',
     *      'database' => 'xx',
     *      'compression' => 'gzip',
     * ]
     * @var array
     */
    public $restore = [];

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
    public $storage = '';

    /**
     * @var string
     */
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
        if (isset($this->backup[$name])) {
            $backup = $this->backup[$name];
            $this->backupManager->backup(
                $backup['database'],
                $this->getDestinations($backup['destinations']),
                $backup['compression'] ?? $this->compression
            );
        } else {
            $this->backupManager->backup(
                $this->database,
                $this->getDestinations($this->destinations),
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
        if (isset($this->restore[$name])) {
            $restore = $this->restore[$name];
            $this->backupManager->restore(
                $restore['storage'],
                $restore['storagePath'],
                $restore['database'],
                $restore['compression'] ?? $this->compression
            );
        } else {
            $this->backupManager->restore(
                $this->storage,
                $this->storagePath,
                $this->database,
                $this->compression
            );
        }
    }

    /**
     * @param array $destinations
     * @return array
     * @inheritdoc
     */
    public function getDestinations(array $destinations): array
    {
        foreach ($destinations as $index => $destination) {
            if (is_string($destination)) {
                [$name, $path] = explode(':', $destination);
                $path = strtr($path, [
                    '{date}' => date('ymd'),
                    '{time}' => date('His'),
                ]);
                $destinations[$index] = new Destination($name, $path);
            }
        }
        return $destinations;
    }
}
