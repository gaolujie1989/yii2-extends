<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use lujie\extend\db\OffsetBatchQueryResult;
use Yii;
use yii\db\BatchQueryResult;
use yii\db\Query;

/**
 * Class QueryHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class QueryHelper
{
    /**
     * @param Query $query
     * @return string
     * @inheritdoc
     */
    public static function buildSql(Query $query): string
    {
        [$sql, $params] = Yii::$app->getDb()->getQueryBuilder()->build($query);
        foreach ($params as $key => $param) {
            $params[$key] = '\'' . $param . '\'';
        }
        $params = array_merge($params, ['{{%' => '', '{{' => '', '}}' => '', '[[' => '', ']]' => '']);
        return strtr($sql, $params);
    }

    /**
     * @param Query $query
     * @param int $batchSize
     * @param int $limit
     * @param null $db
     * @return BatchQueryResult|object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function batch(Query $query, int $batchSize = 100, int $limit = 10, $db = null): BatchQueryResult
    {
        return Yii::createObject([
            'class' => OffsetBatchQueryResult::class,
            'query' => $query,
            'batchSize' => $batchSize,
            'limit' => $limit,
            'db' => $db,
            'each' => false,
        ]);
    }

    /**
     * @param Query $query
     * @param int $batchSize
     * @param int $limit
     * @param null $db
     * @return BatchQueryResult|object
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public static function each(Query $query, int $batchSize = 100, int $limit = 10, $db = null): BatchQueryResult
    {
        return Yii::createObject([
            'class' => OffsetBatchQueryResult::class,
            'query' => $query,
            'batchSize' => $batchSize,
            'limit' => $limit,
            'db' => $db,
            'each' => true,
        ]);
    }
}
