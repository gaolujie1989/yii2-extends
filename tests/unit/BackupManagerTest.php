<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\backup\manager\tests;

use lujie\backup\manager\BackupManager;
use Yii;
use yii\helpers\FileHelper;

/**
 * Class BackupManagerTest
 * @package lujie\backup\manager\tests
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BackupManagerTest extends \Codeception\Test\Unit
{
    /**
     * @return BackupManager
     * @inheritdoc
     */
    protected function getBackupManager(): BackupManager
    {
        return new BackupManager([
            'databases' => [
                'db' => 'db',
                'db2' => [
                    'db',
                    'singleTransaction' => false,
                    'ignoreTables' => ['data_record', 'data_record_data', 'data_source'],
                ],
            ],
            'storages' => [
                'local' => [
                    'type' => 'Local',
                    'root' => Yii::getAlias('@dbBackup'),
                ],
                'fs' => 'filesystem',
            ],
            'backup' => [
                'db' => [
                    'database' => 'db',
                    'destinations' => [
                        'local:db_{date}_{time}.sql',
                    ],
                    'compression' => 'gzip'
                ],
                'db2' => [
                    'database' => 'db2',
                    'destinations' => [
                        'fs:backup/yii2ext/db2_{date}_{time}.sql',
                    ],
                    'compression' => 'gzip'
                ],
            ]
        ]);
    }

    /**
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @inheritdoc
     */
    public function testMe(): void
    {
        $backupManager = $this->getBackupManager();
        $backupManager->runBackup('db');
        $backupManager->runBackup('db2');
    }
}