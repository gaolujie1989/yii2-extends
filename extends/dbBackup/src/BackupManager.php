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
use BackupManager\Filesystems\Destination;
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
use lujie\backup\manager\Filesystems\AliyunOssFilesystem;
use lujie\backup\manager\Filesystems\QCloudCosFilesystem;
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
     * 'xxx' => [
     *      'database' => 'xx',
     *      'destinations' => [],
     *      'compression' => 'gzip',
     * ]
     * @var array
     */
    public $backup = [
        'db' => [
            'database' => 'db',
            'destinations' => [
                'local:db_{date}_{time}.sql',
            ],
            'compression' => 'gzip'
        ],
    ];

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
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        foreach ($this->databases as $key => $config) {
            $this->databases[$key] = $this->getDatabaseConfig($config);
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
        $filesystems->add(new LocalFilesystem());
        $filesystems->add(new AliyunOssFilesystem());
        $filesystems->add(new QCloudCosFilesystem());
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
     * @param string $database
     * @param array $destinations
     * @param string $compression
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @inheritdoc
     */
    public function backup(string $database, array $destinations, string $compression): void
    {
        $this->manager->makeBackup()->run($database, $this->getDestinations($destinations), $compression);
    }

    /**
     * @param string $sourceType
     * @param string $sourcePath
     * @param string $databaseName
     * @param string|null $compression
     * @throws \BackupManager\Compressors\CompressorTypeNotSupported
     * @throws \BackupManager\Config\ConfigFieldNotFound
     * @throws \BackupManager\Config\ConfigNotFoundForConnection
     * @throws \BackupManager\Databases\DatabaseTypeNotSupported
     * @throws \BackupManager\Filesystems\FilesystemTypeNotSupported
     * @inheritdoc
     */
    public function restore(string $sourceType, string $sourcePath, string $databaseName, ?string $compression = null): void
    {
        $this->manager->makeRestore()->run($sourceType, $sourcePath, $databaseName, $compression);
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
    public function runBackup(string $name): void
    {
        if (empty($this->backup[$name])) {
            throw new InvalidArgumentException('Invalid backup name');
        }
        $backup = $this->backup[$name];
        $this->backup(
            $backup['database'],
            $this->getDestinations($backup['destinations']),
            $backup['compression']
        );
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
    public function runRestore(string $name): void
    {
        if (empty($this->restore[$name])) {
            throw  new InvalidArgumentException('Invalid restore name');
        }
        $restore = $this->restore[$name];
        $this->restore(
            $restore['storage'],
            $restore['storagePath'],
            $restore['database'],
            $restore['compression']
        );
    }

    /**
     * @param string|array $config
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function getDatabaseConfig($config): array
    {
        if (is_string($config)) {
            /** @var Connection $db */
            $db = Instance::ensure($config, Connection::class);
            $config = [];
        } else if (is_array($config) && (isset($config['db']) || isset($config[0]))) {
            $db = Instance::ensure($config['db'] ?? $config[0], Connection::class);
            unset($config['db'], $config[0]);
        } else {
            return $config;
        }

        $driverName = $db->getDriverName();
        if (!in_array($driverName, ['mysql', 'pgsql'], true)) {
            throw new InvalidConfigException('Invalid db');
        }

        $dsn = substr($db->dsn, strpos($db->dsn, ':') + 1);
        $dsnParts = explode(';', $dsn);
        foreach ($dsnParts as $dsnPart) {
            [$k, $v] = explode('=', $dsnPart);
            $config[$k] = $v;
        }

        return [
            'type' => $driverName,
            'host' => $config['host'],
            'port' => $config['port'] ?? ($driverName === 'mysql' ? 3306 : 5432),
            'user' => $db->username,
            'pass' => $db->password,
            'database' => $config['dbname'],
            'singleTransaction' => $config['singleTransaction'] ?? true,
            // ignore tables only support mysql
            'ignoreTables' => $config['ignoreTables'] ?? null,
            // add additional options to dump-command (like '--max-allowed-packet')
            'extraParams' => $config['extraParams'] ?? '',
        ];
    }

    /**
     * @param string|array $config
     * @return array
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function getStorageConfig($config): array
    {
        $filesystem = Instance::ensure($config, Filesystem::class);
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
        if ($filesystem instanceof \lujie\flysystem\QCloudCosFilesystem) {
            return [
                'type' => 'qCloudCos',
                'region' => $filesystem->region,
                'credentials' => [
                    'appId' => $filesystem->appId,
                    'secretId' => $filesystem->accessId,
                    'secretKey' => $filesystem->accessSecret,
                    'token' => $filesystem->token,
                ],

                'bucket' => $filesystem->bucket,
                'timeout' => $filesystem->timeout,
                'connect_timeout' => $filesystem->connectTimeout,
                'cdn' => '',
                'scheme' => 'https',
                'read_from_cdn' => false,
                'cdn_key' => '',
                'encrypt' => false,
            ];
        }
        throw new InvalidArgumentException('Invalid filesystem to get config');
    }

    /**
     * @param array $destinations
     * @return array
     * @inheritdoc
     */
    protected function getDestinations(array $destinations): array
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
