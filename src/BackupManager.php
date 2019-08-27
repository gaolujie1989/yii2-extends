<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\backup\manager;

use BackupManager\Compressors\CompressorProvider;
use BackupManager\Compressors\GzipCompressor;
use BackupManager\Compressors\NullCompressor;
use BackupManager\Config\Config;
use BackupManager\Databases\DatabaseProvider;
use BackupManager\Databases\MysqlDatabase;
use BackupManager\Databases\PostgresqlDatabase;
use BackupManager\Filesystems\Awss3Filesystem;
use BackupManager\Filesystems\DropboxFilesystem;
use BackupManager\Filesystems\DropboxV2Filesystem;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\Filesystems\FtpFilesystem;
use BackupManager\Filesystems\GcsFilesystem;
use BackupManager\Filesystems\LocalFilesystem;
use BackupManager\Filesystems\RackspaceFilesystem;
use BackupManager\Filesystems\SftpFilesystem;
use BackupManager\Manager;
use creocoder\flysystem\Filesystem;
use lujie\flysystem\BackupManager\Filesystems\AliyunOssFilesystem;
use yii\base\Component;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\di\Instance;

/**
 * Class BackupManager
 * @package lujie\backup\manager
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class BackupManager extends Component
{
    /**
     * @var Manager
     */
    private $manager;

    /**
     * @var array
     */
    public $databases;

    /**
     * @var array
     */
    public $storages;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->databases as $key => $config) {
            if (is_string($config)) {
                $this->databases[$key] = $this->getDatabaseConfig($config);
            }
        }
        foreach ($this->storages as $key => $config) {
            if (is_string($config)) {
                $this->storages[$key] = $this->getStorageConfig($config);
            }
        }
        $this->initManager();
    }

    /**
     * @inheritdoc
     */
    public function initManager(): void
    {
        $filesystems = new FilesystemProvider(new Config($this->storages));
        $filesystems->add(new AliyunOssFilesystem());
        $filesystems->add(new LocalFilesystem());
        $filesystems->add(new Awss3Filesystem());
        $filesystems->add(new RackspaceFilesystem());
        $filesystems->add(new GcsFilesystem());
        $filesystems->add(new DropboxFilesystem());
        $filesystems->add(new DropboxV2Filesystem());
        $filesystems->add(new FtpFilesystem());
        $filesystems->add(new SftpFilesystem());

        $databases = new DatabaseProvider(new Config($this->databases));
        $databases->add(new MysqlDatabase());
        $databases->add(new PostgresqlDatabase());

        $compressors = new CompressorProvider();
        $compressors->add(new GzipCompressor());
        $compressors->add(new NullCompressor());

        $this->manager = new Manager($filesystems, $databases, $compressors);
    }

    /**
     * @param $database
     * @param array $destinations
     * @param $compression
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @inheritdoc
     */
    public function backup(string $database, array $destinations, string $compression): void
    {
        $this->manager->makeBackup()->run($database, $destinations, $compression);
    }

    /**
     * @param $sourceType
     * @param $sourcePath
     * @param $databaseName
     * @param null $compression
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @inheritdoc
     */
    public function restore($sourceType, $sourcePath, $databaseName, $compression = null): void
    {
        $this->manager->makeRestore()->run($sourceType, $sourcePath, $databaseName, $compression);
    }

    /**
     * @param $db
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function getDatabaseConfig($db): array
    {
        /** @var Connection $db */
        $db = Instance::ensure($db, Connection::class);
        $driverName = $db->getDriverName();
        if (in_array($driverName, ['mysql', 'pgsql'], true)) {
            throw new InvalidArgumentException('Invalid db');
        }

        $dbConfig = [];
        $dsn = substr($db->dsn, strpos($db->dsn, ':') + 1);
        $dsnParts = explode(';', $dsn);
        foreach ($dsnParts as $dsnPart) {
            [$k, $v] = explode('=', $dsnPart);
            $dbConfig[$k] = $v;
        }

        return [
            'type' => $driverName,
            'host' => $dbConfig['host'],
            'port' => $dbConfig['port'] ?? ($driverName === 'mysql' ? 3306 : 5432),
            'user' => $db->username,
            'pass' => $db->password,
            'database' => $dbConfig['dbname'],
            'singleTransaction' => true,
            // ignore tables only support mysql
            'ignoreTables' => null,
            // add additional options to dump-command (like '--max-allowed-packet')
            'extraParams' => '',
        ];
    }

    /**
     * @param $filesystem
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function getStorageConfig($filesystem): array
    {
        $filesystem = Instance::ensure($filesystem, Filesystem::class);
        if ($filesystem instanceof \creocoder\flysystem\LocalFilesystem) {
            return [
                'type' => 'Local',
                'root' => $filesystem->path,
            ];
        }
        if ($filesystem instanceof \lujie\flysystem\AliyunOssFilesystem) {
            return [
                'type' => 'aliyunOss',
                'bucket' => $filesystem->bucket,
                'endpoint' => $filesystem->endpoint ?: 'oss-cn-hangzhou.aliyuncs.com',
                'timeout' => $filesystem->timeout,
                'connectTimeout' => $filesystem->connectTimeout,
                'isCName' => $filesystem->isCName,
                'token' => $filesystem->token,
                'accessId' => $filesystem->accessId,
                'accessSecret' => $filesystem->accessSecret,
            ];
        }
        throw new InvalidArgumentException('Invalid filesystem to get config');
    }
}
