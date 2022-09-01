<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\db;

use yii\db\ActiveQuery;
use yii\db\BaseActiveRecord;
use yii\db\Command;
use yii\helpers\ArrayHelper;

/**
 * Class ShardingActiveQuery
 *
 * @property BaseActiveRecord|ShardingActiveRecordTrait $modelClass
 *
 * @package lujie\sharding\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait ShardingActiveQueryTrait
{
    /**
     * @var array
     */
    public $shardingValues = [];

    /**
     * @param $condition
     * @inheritdoc
     */
    public function setShardingValues(array $condition, $append = true): void
    {
        if (!$append) {
            $this->shardingValues = [];
        }
        if (isset($condition[0], $condition[1], $condition[2])) {
            $this->shardingValues[$condition[1]] = $condition[2];
        }
        if (ArrayHelper::isAssociative($condition)) {
            $this->shardingValues = array_merge($this->shardingValues, $condition);
        }
    }

    /**
     * @param $condition
     * @param array $params
     * @return ActiveQuery|ShardingActiveQueryTrait
     * @inheritdoc
     */
    public function where($condition, $params = []): ActiveQuery
    {
        if (is_array($condition)) {
            $this->setShardingValues($condition, false);
        }
        return parent::where($condition, $params);
    }

    /**
     * @param $condition
     * @param array $params
     * @return ActiveQuery|ShardingActiveQueryTrait
     * @inheritdoc
     */
    public function andWhere($condition, $params = []): ActiveQuery
    {
        if (is_array($condition)) {
            $this->setShardingValues($condition);
        }
        return parent::andWhere($condition, $params);
    }

    /**
     * @param null $db
     * @return \yii\db\Command
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function createCommand($db = null): Command
    {
        $this->modelClass::setShardingSuffix($this->shardingValues);
        return parent::createCommand($db);
    }
}
