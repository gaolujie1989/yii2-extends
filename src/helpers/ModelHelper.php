<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\helpers;


use lujie\alias\behaviors\AliasPropertyBehavior;
use lujie\alias\behaviors\MoneyAliasBehavior;
use lujie\alias\behaviors\TimestampAliasBehavior;
use lujie\alias\behaviors\UnitAliasBehavior;
use lujie\ar\relation\behaviors\RelationSavableBehavior;
use yii\db\ActiveQueryInterface;
use yii\db\ActiveRecordInterface;
use yii\db\BaseActiveRecord;
use yii\helpers\StringHelper;

/**
 * Class ModelHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
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
     * @param array $keys
     * @param bool $prefix
     * @return array
     * @inheritdoc
     */
    private static function filterAttributes(BaseActiveRecord $model, array $keys, bool $prefix = true): array
    {
        return array_filter($model->attributes(), static function($attribute) use ($keys, $prefix) {
            foreach ($keys as $key) {
                if ($attribute === $key
                    || ($prefix && StringHelper::startsWith($attribute, $key . '_', true))
                    || (!$prefix && StringHelper::endsWith($attribute, '_' . $key, true))) {
                    $filterAttributes[] = $attribute;
                    return true;
                }
            }
            return false;
        });
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
        $filterAttributes = static::filterAttributes($model, $filterKeySuffixes, false);
        $likeAttributes = static::filterAttributes($model, $likeKeySuffixes, false);
        $rangeAttributes = static::filterAttributes($model, $rangeKeySuffixes, false);

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
        $filterAttributes = static::filterAttributes($model, $filterKeySuffixes, false);
        $datetimeAttributes = static::filterAttributes($model, $datetimeKeySuffixes, false);

        $rules = [];
        if ($filterAttributes) {
            $rules[] = [$filterAttributes, 'safe'];
        }
        if ($datetimeAttributes) {
            $rules[] = [$datetimeAttributes, 'each', 'rule' => ['date']];
        }
        return $rules;
    }

    /**
     * @param BaseActiveRecord $model
     * @param array $rules
     * @param array|string[] $removeKeySuffixes
     * @return array
     * @inheritdoc
     */
    public static function formRules(BaseActiveRecord $model, array $rules = [], array $removeKeySuffixes = ['at', 'cent', 'g', 'mm', 'mm3', 'mm3', 'additional']): array
    {
        $rules = $rules ?: $model->rules();
        $removeRuleAttributes = static::filterAttributes($model, $removeKeySuffixes, false);
        $removeRuleAttributes = [$removeRuleAttributes];

        $aliasSafeRules = [];
        $aliasNumberRules = [];
        $aliasDatetimeRules = [];
        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior instanceof AliasPropertyBehavior) {
                $removeRuleAttributes[] = $behavior->aliasProperties;
                if ($behavior instanceof MoneyAliasBehavior || $behavior instanceof UnitAliasBehavior) {
                    $aliasNumberRules[] = array_keys($behavior->aliasProperties);
                } else if ($behavior instanceof TimestampAliasBehavior) {
                    $aliasDatetimeRules[] = array_keys($behavior->aliasProperties);
                } else {
                    $aliasSafeRules[] = array_keys($behavior->aliasProperties);
                }
            } else if ($behavior instanceof RelationSavableBehavior) {
                $aliasSafeRules[] = $behavior->relations;
            }
        }
        if ($removeRuleAttributes){
            static::removeAttributesRules($rules, array_merge(...$removeRuleAttributes));
        }
        if ($aliasSafeRules) {
            $rules[] = [array_merge(...$aliasSafeRules), 'safe'];
        }
        if ($aliasNumberRules) {
            $rules[] = [array_merge(...$aliasNumberRules), 'number'];
        }
        if ($aliasDatetimeRules) {
            $rules[] = [array_merge(...$aliasDatetimeRules), 'date'];
        }

        return $rules;
    }
}
