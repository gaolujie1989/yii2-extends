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
use BackupManager\Filesystems\Destination;
use BackupManager\Filesystems\FilesystemProvider;
use BackupManager\Manager;
use lujie\backup\manager\databases\YiiComponentDatabase;
use lujie\backup\manager\filesystems\LocalFilesystem;
use lujie\backup\manager\filesystems\YiiComponentFilesystem;
use lujie\extend\flysystem\Filesystem;
use Yii;
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
     * @var string[]
     */
    public $filesystems = [
        LocalFilesystem::class,
        YiiComponentFilesystem::class,
    ];

    /**
     * @var array
     */
    public $storages;

    /**
     * @var array
     */
    public $databases;

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
     * @var Manager
     */
    private $manager;

    /**
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->initManager();
    }

    /**
     * @inheritdoc
     */
    protected function initManager(): void
    {
        $filesystems = new FilesystemProvider(new Config($this->storages));
        foreach ($this->filesystems as $filesystemClass) {
            $filesystems->add(new $filesystemClass());
        }

        $databases = new DatabaseProvider(new Config($this->databases));
        $databases->add(new MysqlDatabase());
        $databases->add(new PostgresqlDatabase());
        $databases->add(new YiiComponentDatabase());

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
