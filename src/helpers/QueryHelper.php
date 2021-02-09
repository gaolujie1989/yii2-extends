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

    /**
     * @param Query $query
     * @param array $attributeValues
     * @param string $alias
     */
    public static function filterRange(Query $query, array $attributeValues, string $alias = ''): void
    {
        $alias = $alias ? $alias . '.' : '';
        foreach ($attributeValues as $attribute => $value) {
            if ($value && is_array($value)) {
                $aliasAttribute = $alias . $attribute;
                $query->andFilterWhere(['>=', $aliasAttribute, $value[0] ?? ''])
                    ->andFilterWhere(['<=', $aliasAttribute, $value[1] ?? '']);
            }
        }
    }

    /**
     * @param Query $query
     * @param array $timeAttributeValues
     * @param string $alias
     * @deprecated
     */
    public static function filterTimestampRange(Query $query, array $timeAttributeValues, string $alias = ''): void
    {
        static::filterRange($query, $timeAttributeValues, $alias);
    }

    /**
     * @param Query $query
     * @param array $attributeValues
     * @param bool $like
     * @param false $orLike
     * @param string $alias
     * @param string $splitPattern
     * @inheritdoc
     */
    public static function filterValue(Query $query, array $attributeValues,
                                       $like = false, string $alias = '',
                                       string $splitPattern = '/[,;\s]/'): void
    {
        $alias = $alias ? $alias . '.' : '';
        foreach ($attributeValues as $attribute => $value) {
            $aliasAttribute = $alias . $attribute;
            if (is_array($value)) {
                $query->andFilterWhere([$aliasAttribute => $value]);
                continue;
            }

            $values = preg_split($splitPattern, $value, -1, PREG_SPLIT_NO_EMPTY);
            $values = array_filter(array_map('trim', $values));
            if (empty($values)) {
                continue;
            }
            if ($like) {
                if ($like === 'L') {
                    $values = array_map(static function($v) {
                        return '%'. $v;
                    }, $values);
                    $query->andFilterWhere(['OR LIKE', $aliasAttribute, $values, false]);
                } else if ($like === 'R') {
                    $values = array_map(static function($v) {
                        return $v . '%';
                    }, $values);
                    $query->andFilterWhere(['OR LIKE', $aliasAttribute, $values, false]);
                } else {
                    $query->andFilterWhere(['OR LIKE', $aliasAttribute, $values]);
                }
            } else {
                $query->andFilterWhere([$aliasAttribute => $values]);
            }
        }
    }
}
