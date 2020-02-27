<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sharding\db;

use yii\base\NotSupportedException;
use yii\db\ActiveQuery;
use yii\db\Command;
use yii\helpers\ArrayHelper;

/**
 * Class ShardingActiveQuery
 *
 * @property ShardingActiveRecord $modelClass
 *
 * @package lujie\sharding\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ShardingActiveQuery extends ActiveQuery
{
    /**
     * @var array
     */
    public $shardingValues = [];

    /**
     * @param array|string|\yii\db\ExpressionInterface $condition
     * @param array $params
     * @return ShardingActiveQuery|ActiveQuery
     * @inheritdoc
     */
    public function andWhere($condition, $params = []): ShardingActiveQuery
    {
        if (ArrayHelper::isAssociative($condition)) {
            $this->shardingValues = array_merge($this->shardingValues, $condition);
        }
        return parent::andWhere($condition, $params); // TODO: Change the autogenerated stub
    }

    /**
     * @param array|string|\yii\db\ExpressionInterface $condition
     * @param array $params
     * @return void|ActiveQuery
     * @throws NotSupportedException
     * @inheritdoc
     */
    public function orWhere($condition, $params = [])
    {
        throw new NotSupportedException('Or where not support for sharding query');
    }

    /**
     * @param null $db
     * @return \yii\db\Command
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function createCommand($db = null): Command
    {
        $this->modelClass::setShardingTableSuffix($this->shardingValues);
        $this->modelClass::setShardingDbSuffix($this->shardingValues);
        return parent::createCommand($db); // TODO: Change the autogenerated stub
    }
}
