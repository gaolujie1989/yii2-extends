<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;

use lujie\extend\db\OffsetBatchQueryResult;
use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\db\BatchQueryResult;
use yii\db\Query;
use yii\db\QueryInterface;

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
            if (is_string($param)) {
                $params[$key] = '\'' . $param . '\'';
            }
        }
        $params = array_merge($params, ['{{%' => '', '{{' => '', '}}' => '', '[[' => '', ']]' => '']);
        return strtr($sql, $params);
    }

    /**
     * @param Query $query
     * @return string
     * @inheritdoc
     */
    public static function getAlias(Query $query): string
    {
        if (empty($query->from)) {
            return '';
        }
        if (count($query->from) === 1) {
            $alias = array_keys($query->from)[0];
            return is_string($alias) ? $alias : '';
        }
        if ($query instanceof ActiveQueryInterface && count($query->from) > 1) {
            /** @var ActiveQuery $query */
            /** @var ActiveRecord $modelClass */
            $modelClass = $query->modelClass;
            foreach ($query->from as $alias => $tableName) {
                if (is_string($alias) && $tableName === $modelClass::tableName()) {
                    return $alias;
                }
            }
        }
        return '';
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
     * @param QueryInterface $query
     * @param array $attributeValues
     * @param string $alias
     * @inheritdoc
     */
    public static function filterRange(QueryInterface $query, array $attributeValues, string $alias = ''): void
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
     * @deprecated use filterRange instead
     */
    public static function filterTimestampRange(Query $query, array $timeAttributeValues, string $alias = ''): void
    {
        static::filterRange($query, $timeAttributeValues, $alias);
    }

    /**
     * @param QueryInterface $query
     * @param array $attributeValues
     * @param bool|string $like
     * @param string $alias
     * @param string $splitPattern
     * @inheritdoc
     */
    public static function filterValue(
        QueryInterface $query,
        array          $attributeValues,
                       $like = false,
        string         $alias = '',
        string         $splitPattern = '/[,;\s]/'
    ): void
    {
        $alias = $alias ? $alias . '.' : '';
        foreach ($attributeValues as $attribute => $value) {
            if (ValueHelper::isEmpty($value)) {
                continue;
            }
            $aliasAttribute = $alias . $attribute;
            if (is_array($value)) {
                $query->andFilterWhere([$aliasAttribute => $value]);
                continue;
            }

            $values = ValueHelper::strToArray((string)$value, $splitPattern);
            if (empty($values)) {
                continue;
            }
            if ($like) {
                if ($like === 'L') {
                    $valuesL = array_map(static function ($v) {
                        return $v . '%';
                    }, $values);
                    $query->andFilterWhere(['OR LIKE', $aliasAttribute, $valuesL, false]);
                } elseif ($like === 'R') {
                    $valuesR = array_map(static function ($v) {
                        return '%' . $v;
                    }, $values);
                    $query->andFilterWhere(['OR LIKE', $aliasAttribute, $valuesR, false]);
                } elseif ($like === 'LR') {
                    $valuesL = array_map(static function ($v) {
                        return $v . '%';
                    }, $values);
                    $valuesR = array_map(static function ($v) {
                        return '%' . $v;
                    }, $values);
                    $query->andFilterWhere(['OR LIKE', $aliasAttribute, array_merge($valuesL, $valuesR), false]);
                } else {
                    $query->andFilterWhere(['OR LIKE', $aliasAttribute, $values]);
                }
            } else {
                $query->andFilterWhere([$aliasAttribute => $values]);
            }
        }
    }

    /**
     * @param QueryInterface $query
     * @param array $attributes
     * @param mixed $value
     * @param bool|string $like
     * @param string $alias
     * @param string $splitPattern
     * @inheritdoc
     */
    public static function filterKey(
        QueryInterface $query,
        array          $attributes,
                       $value,
                       $like = false,
        string         $alias = '',
        string         $splitPattern = '/[,;\s]/'
    ): void
    {
        if (ValueHelper::isEmpty($value)) {
            return;
        }
        $alias = $alias ? $alias . '.' : '';
        $condition = ['OR'];
        if (is_array($value)) {
            foreach ($attributes as $attribute) {
                $aliasAttribute = $alias . $attribute;
                $condition[] = [$aliasAttribute => $value];
            }
            $query->andFilterWhere($condition);
            return;
        }
        $values = preg_split($splitPattern, $value, -1, PREG_SPLIT_NO_EMPTY);
        $values = array_filter(array_map('trim', $values));
        if (empty($values)) {
            return;
        }
        if ($like) {
            foreach ($attributes as $attribute) {
                $aliasAttribute = $alias . $attribute;
                if ($like === 'L') {
                    $values = array_map(static function ($v) {
                        return '%' . $v;
                    }, $values);
                    $condition[] = ['OR LIKE', $aliasAttribute, $values, false];
                } elseif ($like === 'R') {
                    $values = array_map(static function ($v) {
                        return $v . '%';
                    }, $values);
                    $condition[] = ['OR LIKE', $aliasAttribute, $values, false];
                } else {
                    $condition[] = ['OR LIKE', $aliasAttribute, $values];
                }
            }
            $query->andFilterWhere($condition);
        } else {
            foreach ($attributes as $attribute) {
                $aliasAttribute = $alias . $attribute;
                $condition[] = [$aliasAttribute => $value];
            }
            $query->andFilterWhere($condition);
        }
    }
}
