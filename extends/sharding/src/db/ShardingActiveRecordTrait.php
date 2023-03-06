<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\db;

use lujie\sharding\rules\BaseShardingRule;
use yii\base\InvalidConfigException;
use yii\db\Connection;
use yii\di\Instance;

/**
 * Class ShardingActiveRecord
 * @package lujie\sharding\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ShardingActiveRecordTrait
{
    use ShardingChosenTrait;

    /**
     * support multi key, for extend custom sharding
     * @var array
     */
    public static $tableShardingRules = [];

    /**
     * @var array
     */
    public static $dbShardingRules = [];

    /**
     * @var string
     */
    private static $shardingTableSuffix = '';

    /**
     * @var string
     */
    private static $shardingDbSuffix = '';

    #region static methods for sharding table/db name rule

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected static function initShardingRules(): void
    {
        foreach (static::$tableShardingRules as $key => $rule) {
            if (!($rule instanceof BaseShardingRule)) {
                static::$tableShardingRules[$key] = Instance::ensure($rule, BaseShardingRule::class);
            }
        }
        foreach (static::$dbShardingRules as $key => $rule) {
            if (!($rule instanceof BaseShardingRule)) {
                static::$dbShardingRules[$key] = Instance::ensure($rule, BaseShardingRule::class);
            }
        }
    }

    /**
     * @param BaseShardingRule[] $shardingRules
     * @param array $values
     * @return string
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected static function getShardingSuffix(array $shardingRules, array $values): string
    {
        $tableParts = [];
        foreach ($shardingRules as $shardingKey => $shardingRule) {
            $value = $values[$shardingKey] ?? null;
            if ($value === null) {
                throw new InvalidConfigException('Value of sharding key must be set');
            }
            $tableParts[] = $shardingRule->getSuffix($value);
        }
        return implode('', $tableParts);
    }

    /**
     * @param array $values
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function setShardingSuffix(array $values): void
    {
        static::initShardingRules();
        static::$shardingTableSuffix = static::getShardingSuffix(static::$tableShardingRules, $values);
        static::$shardingDbSuffix = static::getShardingSuffix(static::$dbShardingRules, $values);
    }

    /**
     * @param string $tableName
     * @return string
     * @inheritdoc
     */
    protected static function getShardingTableName(string $tableName): string
    {
        if (substr($tableName, -2) === '}}') {
            return substr($tableName, 0, -2) . static::$shardingTableSuffix . '}}';
        }
        return $tableName . static::$shardingTableSuffix;
    }

    /**
     * @param string $dbName
     * @return Connection|object
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected static function getShardingDb(string $dbName): Connection
    {
        $dbName .= static::$shardingDbSuffix;
        return Instance::ensure($dbName, Connection::class);
    }

    #endregion

    #region overwrite, set shardingTableDbSuffix before execute db

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function beforeValidate(): bool
    {
        static::setShardingSuffix($this->shardingValues ?: $this->attributes);
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
        static::setShardingSuffix($this->shardingValues ?: $this->attributes);
        return parent::beforeSave($insert);
    }

    /**
     * @return bool
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function beforeDelete(): bool
    {
        static::setShardingSuffix($this->shardingValues ?: $this->attributes);
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
        static::setShardingSuffix($this->shardingValues ?: $this->attributes);
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
        static::setShardingSuffix($this->shardingValues ?: $this->attributes);
        return parent::updateCounters($counters);
    }

    /**
     * @param array $attributes
     * @param string|array $condition
     * @param array $params
     * @return int
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function updateAll($attributes, $condition = '', $params = []): int
    {
        $shardingValues = $params['sharding'] ?? (array)$condition;
        unset($params['sharding']);
        static::setShardingSuffix($shardingValues);
        return parent::updateAll($attributes, $condition, $params);
    }

    /**
     * @param array $counters
     * @param string|array $condition
     * @param array $params
     * @return int
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function updateAllCounters($counters, $condition = '', $params = []): int
    {
        $shardingValues = $params['sharding'] ?? (array)$condition;
        unset($params['sharding']);
        static::setShardingSuffix($shardingValues);
        return parent::updateAllCounters($counters, $condition, $params);
    }

    /**
     * @param ?string|array $condition
     * @param array $params
     * @return int
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public static function deleteAll($condition = null, $params = []): int
    {
        $shardingValues = $params['sharding'] ?? (array)$condition;
        unset($params['sharding']);
        static::setShardingSuffix($shardingValues);
        return parent::deleteAll($condition, $params);
    }

    #endregion


}
