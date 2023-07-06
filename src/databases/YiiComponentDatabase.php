<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\backup\manager\databases;

use BackupManager\Databases\Database;
use BackupManager\Databases\MysqlDatabase;
use BackupManager\Databases\PostgresqlDatabase;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\Connection;
use yii\di\Instance;

/**
 * Class YiiComponentFilesystem
 * @package lujie\backup\manager\Filesystems
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class YiiComponentDatabase implements Database
{
    public $type = 'component';

    /**
     * @var Database
     */
    private $database;

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
     * @throws InvalidConfigException
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function setConfig(array $config): void
    {
        $component = $config['component'] ?? null;
        /** @var Connection $connection */
        $connection = Instance::ensure($component, Connection::class);
        $driverName = $connection->getDriverName();
        if (!in_array($driverName, ['mysql', 'pgsql'], true)) {
            throw new NotSupportedException("DB driver '{$driverName}' is not supported.");
        }

        $dsn = substr($connection->dsn, strpos($connection->dsn, ':') + 1);
        $dsnParts = explode(';', $dsn);
        foreach ($dsnParts as $dsnPart) {
            [$k, $v] = explode('=', $dsnPart);
            $config[$k] = $v;
        }

        $config = [
            'type' => $driverName,
            'host' => $config['host'],
            'port' => $config['port'] ?? ($driverName === 'mysql' ? 3306 : 5432),
            'user' => $connection->username,
            'pass' => $connection->password,
            'database' => $config['dbname'],
            'singleTransaction' => $config['singleTransaction'] ?? true,
            // ignore tables only support mysql
            'ignoreTables' => $config['ignoreTables'] ?? null,
            // add additional options to dump-command (like '--max-allowed-packet')
            'extraParams' => $config['extraParams'] ?? '',
        ];

        $this->database = $driverName === 'mysql' ? new MysqlDatabase() : new PostgresqlDatabase();
        $this->database->setConfig($config);
    }

    /**
     * @param $inputPath
     * @return string
     * @inheritdoc
     */
    public function getDumpCommandLine($inputPath): string
    {
        return $this->database->getDumpCommandLine($inputPath);
    }

    /**
     * @param $outputPath
     * @return string
     * @inheritdoc
     */
    public function getRestoreCommandLine($outputPath): string
    {
        return $this->database->getRestoreCommandLine($outputPath);
    }
}
