<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;


use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecord;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;
use yii\db\Query;

class ModelHelper
{
    /**
     * @param array $rules
     * @param array|string $attributes
     * @param string|null $rule
     * @return array
     * @inheritdoc
     */
    public static function removeAttributesRules(array &$rules, $attributes, ?string $rule = null): array
    {
        $attributes = (array)$attributes;
        foreach ($rules as $key => $ruleConfig) {
            [$ruleAttributes, $ruleName] = $ruleConfig;
            if ($rule === null || $rule === $ruleName) {
                if (is_string($ruleAttributes) && in_array($ruleAttributes, $attributes, true)) {
                    unset($rules[$key]);
                } else if (is_array($ruleAttributes) && array_intersect($attributes, $ruleAttributes)) {
                    $ruleAttributes = array_diff($ruleAttributes, $attributes);
                    if ($ruleAttributes) {
                        $rules[$key][0] = $ruleAttributes;
                    } else {
                        unset($rules[$key]);
                    }
                }
            }
        }
        return $rules;
    }

    /**
     * @param array|string|int $ids
     * @param string|ActiveRecordInterface $modelClass
     * @param string $separator
     * @return array|null
     * @inheritdoc
     */
    public static function getCondition($ids, string $modelClass, string $separator = ','): ?array
    {
        if (empty($ids)) {
            return null;
        }

        if (is_string($ids) && $ids) {
            $ids = explode($separator, $ids);
        }

        $pkColumns = $modelClass::primaryKey();
        if (count($pkColumns) > 1) {
            $condition = [];
            foreach ($ids as $values) {
                $values = explode(',', $values);
                if (count($pkColumns) === count($values)) {
                    $condition[] = array_combine($pkColumns, $values);
                }
            }
            if ($condition) {
                array_unshift($condition, 'OR');
                return $condition;
            }
        } elseif ($ids !== null) {
            return [$pkColumns[0] => $ids];
        }

        return null;
    }

    /**
     * @param int|string|array $id
     * @param string|ActiveRecordInterface $modelClass
     * @param string $separator
     * @return ActiveRecordInterface|null
     * @inheritdoc
     */
    public static function findModel($id, string $modelClass, string $separator = ','): ?ActiveRecordInterface
    {
        $keys = $modelClass::primaryKey();
        if (count($keys) > 1) {
            $values = explode($separator, $id);
            if (count($keys) === count($values)) {
                return $modelClass::findOne(array_combine($keys, $values));
            }
        } elseif ($id !== null) {
            return $modelClass::findOne($id);
        }
        return null;
    }

    /**
     * @param BaseActiveRecord $model
     * @param ActiveQueryInterface|null $query
     * @param string $alias
     * @param array|string[] $filterKeySuffixes
     * @param array|string[] $likeKeySuffixes
     * @param array|string[] $rangeKeySuffixes
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public static function query(BaseActiveRecord $model,
                                 ActiveQueryInterface $query = null,
                                 string $alias = '',
                                 array $filterKeySuffixes = ['id', 'type', 'status'],
                                 array $likeKeySuffixes = ['no', 'key', 'code', 'name', 'title'],
                                 array $rangeKeySuffixes = ['at', 'date', 'time']): ActiveQueryInterface
    {
        $filterAttributes = [];
        $likeAttributes = [];
        $rangeAttributes = [];
        foreach ($model->attributes() as $attribute) {
            foreach ($filterKeySuffixes as $keySuffix) {
                if ($attribute === $keySuffix || substr($attribute, -(strlen($keySuffix) + 1)) === '_' . $keySuffix) {
                    $filterAttributes[] = $attribute;
                    continue;
                }
            }
            foreach ($likeKeySuffixes as $keySuffix) {
                if ($attribute === $keySuffix || substr($attribute, -(strlen($keySuffix) + 1)) === '_' . $keySuffix) {
                    $likeAttributes[] = $attribute;
                    continue;
                }
            }
            foreach ($rangeKeySuffixes as $keySuffix) {
                if ($attribute === $keySuffix || substr($attribute, -(strlen($keySuffix) + 1)) === '_' . $keySuffix) {
                    $rangeAttributes[] = $attribute;
                    continue;
                }
            }
        }
        $query = $query ?: $model::find();
        if ($filterAttributes) {
            QueryHelper::filterValue($query, $model->getAttributes($filterAttributes), false, $alias);
        }
        if ($likeAttributes) {
            QueryHelper::filterValue($query, $model->getAttributes($filterAttributes), true, $alias);
        }
        if ($rangeAttributes) {
            QueryHelper::filterRange($query, $model->getAttributes($rangeAttributes), $alias);
        }
        return $query;
    }

    /**
     * @param BaseActiveRecord $model
     * @param array|string[] $filterKeySuffixes
     * @param array|string[] $datetimeKeySuffixes
     * @return array
     * @inheritdoc
     */
    public static function searchRules(BaseActiveRecord $model,
                                       array $filterKeySuffixes = ['id', 'type', 'status', 'no', 'key', 'code', 'name', 'title'],
                                       array $datetimeKeySuffixes = ['at', 'date', 'time']): array
    {
        $filterAttributes = [];
        $datetimeAttributes = [];
        foreach ($model->attributes() as $attribute) {
            foreach ($filterKeySuffixes as $keySuffix) {
                if ($attribute === $keySuffix || substr($attribute, -(strlen($keySuffix) + 1)) === '_' . $keySuffix) {
                    $filterAttributes[] = $attribute;
                    continue;
                }
            }
            foreach ($datetimeKeySuffixes as $keySuffix) {
                if ($attribute === $keySuffix || substr($attribute, -(strlen($keySuffix) + 1)) === '_' . $keySuffix) {
                    $datetimeAttributes[] = $attribute;
                    continue;
                }
            }
        }
        $rules = [];
        if ($filterAttributes) {
            $rules[] = [$filterAttributes, 'safe'];
        }
        if ($datetimeAttributes) {
            $rules[] = [$datetimeAttributes, 'each', 'rule' => ['date']];
        }
        return $rules;
    }
}
