<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\db;

use yii\base\InvalidConfigException;
use yii\db\ActiveRecord;
use yii\db\Connection;
use yii\di\Instance;

/**
 * Class ShardingActiveRecord
 * @package lujie\sharding\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShardingActiveRecord extends ActiveRecord
{
    protected const SHARDING_RULE_MAP = 'map';
    protected const SHARDING_RULE_RANGE = 'range';
    protected const SHARDING_RULE_HASH = 'hash';

    /**
     * support multi key, for extend custom shardinging
     * @var array
     */
    protected static $tableShardingKeys = [
        'type' => [
            self::SHARDING_RULE_MAP,
            'map' => [],
            'default' => 'common'
        ],
        'update_at' => [
            self::SHARDING_RULE_RANGE,
            'range' => ['2019' => ['2019-01-01'], ['2020-01-01']],
            'default' => 'common'
        ],
        'id' => [
            self::SHARDING_RULE_HASH,
            'count' => 4
        ],
    ];

    /**
     * @var array
     */
    protected static $dbShardingKeys = [
        'account_id' => [self::SHARDING_RULE_HASH, 'count' => 4],
    ];

    /**
     * @var string
     */
    private static $shardingTableSuffix = '';

    /**
     * @var string
     */
    private static $shardingDbSuffix = '';

    #region sharding table/db name rule

    /**
     * @return string
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected static function getShardingSuffix($shardingKeys, array $values): string
    {
        $tableParts = [''];
        foreach ($shardingKeys as $shardingKey => $config) {
            $value = $values[$shardingKey];
            if (empty($value)) {
                throw new InvalidConfigException('Value of sharding key can not be empty');
            }
            $rule = array_shift($config);
            if ($rule === self::SHARDING_RULE_MAP) {
                $tableParts[] = $config['map'][$value] ?? $config['default'];
            } elseif ($rule === self::SHARDING_RULE_HASH) {
                $tableParts[] = $value % $config['count'];
            } else {
                throw new InvalidConfigException('Invalid sharding rule');
            }
        }
        return implode('_', $tableParts);
    }

    /**
     * @param mixed $values
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function setShardingTableSuffix($values): void
    {
        static::$shardingTableSuffix = static::getShardingSuffix(static::$tableShardingKeys, $values);
    }

    /**
     * @param mixed $values
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function setShardingDbSuffix($values): void
    {
        static::$shardingDbSuffix = static::getShardingSuffix(static::$dbShardingKeys, $values);
    }

    #endregion

    #region overwrite, set shardingTableDbSuffix before op db

    /**
     * @return string
     * @inheritdoc
     */
    public static function tableName(): string
    {
        return 'table' . static::$shardingTableSuffix;
    }

    /**
     * @return Connection|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function getDb(): Connection
    {
        $dbName = 'db' . static::$shardingDbSuffix;
        return Instance::ensure($dbName, Connection::class);
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function beforeValidate(): bool
    {
        static::setShardingTableSuffix($this);
        static::setShardingDbSuffix($this);
        return parent::beforeValidate();
    }

    /**
     * @param bool $insert
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function beforeSave($insert): bool
    {
        static::setShardingTableSuffix($this);
        static::setShardingDbSuffix($this);
        return parent::beforeSave($insert);
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function beforeDelete(): bool
    {
        static::setShardingTableSuffix($this);
        static::setShardingDbSuffix($this);
        return parent::beforeDelete();
    }

    /**
     * @param array $attributes
     * @return int
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function updateAttributes($attributes): int
    {
        static::setShardingTableSuffix($this);
        static::setShardingDbSuffix($this);
        return parent::updateAttributes($attributes);
    }

    /**
     * @param array $counters
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function updateCounters($counters): bool
    {
        static::setShardingTableSuffix($this);
        static::setShardingDbSuffix($this);
        return parent::updateCounters($counters);
    }

    /**
     * @param array $attributes
     * @param string $condition
     * @param array $params
     * @return int
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function updateAll($attributes, $condition = '', $params = []): int
    {
        static::setShardingTableSuffix($condition);
        static::setShardingDbSuffix($condition);
        return parent::updateAll($attributes, $condition, $params);
    }

    /**
     * @param array $counters
     * @param string $condition
     * @param array $params
     * @return int
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function updateAllCounters($counters, $condition = '', $params = []): int
    {
        static::setShardingTableSuffix($condition);
        static::setShardingDbSuffix($condition);
        return parent::updateAllCounters($counters, $condition, $params);
    }

    /**
     * @return ShardingActiveQuery
     * @inheritdoc
     */
    public static function find(): ShardingActiveQuery
    {
        return new ShardingActiveQuery(static::class);
    }

    #endregion
}
