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
use yii\helpers\ArrayHelper;
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
                } elseif (is_array($ruleAttributes) && array_intersect($attributes, $ruleAttributes)) {
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
     * @param array $attributes
     * @param array $keys
     * @param bool $prefix
     * @return array
     * @inheritdoc
     */
    public static function filterAttributes(array $attributes, array $keys, bool $prefix = true): array
    {
        return array_values(array_filter($attributes, static function ($attribute) use ($keys, $prefix) {
            foreach ($keys as $key) {
                if ($attribute === $key
                    || ($prefix && StringHelper::startsWith($attribute, $key . '_', true))
                    || (!$prefix && StringHelper::endsWith($attribute, '_' . $key, true))) {
                    $filterAttributes[] = $attribute;
                    return true;
                }
            }
            return false;
        }));
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
    public static function query(
        BaseActiveRecord $model,
        ActiveQueryInterface $query = null,
        string $alias = '',
        array $filterKeySuffixes = [],
        array $likeKeySuffixes = [],
        array $rangeKeySuffixes = []
    ): ActiveQueryInterface
    {
        $filterKeySuffixes = array_merge($filterKeySuffixes, [
            'id', 'type', 'group', 'status',
            'country', 'currency', 'language',
            'carrier', 'departure', 'destination',
        ]);
        $likeKeySuffixes = array_merge($likeKeySuffixes, ['no', 'key', 'code', 'name', 'title']);
        $rangeKeySuffixes = array_merge($rangeKeySuffixes, ['at', 'date', 'time']);
        $filterAttributes = static::filterAttributes($model->attributes(), $filterKeySuffixes, false);
        $likeAttributes = static::filterAttributes($model->attributes(), $likeKeySuffixes, false);
        $rangeAttributes = static::filterAttributes($model->attributes(), $rangeKeySuffixes, false);

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
    public static function searchRules(
        BaseActiveRecord $model,
        array $filterKeySuffixes = [],
        array $datetimeKeySuffixes = []
    ): array
    {
        $filterKeySuffixes = array_merge($filterKeySuffixes, [
            'id', 'type', 'group', 'status',
            'country', 'currency', 'language',
            'carrier', 'departure', 'destination',
            'no', 'key', 'code', 'name', 'title',
        ]);
        $datetimeKeySuffixes = array_merge($datetimeKeySuffixes, ['at', 'date', 'time']);
        $filterAttributes = static::filterAttributes($model->attributes(), $filterKeySuffixes, false);
        $datetimeAttributes = static::filterAttributes($model->attributes(), $datetimeKeySuffixes, false);

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
     * @param array $row
     * @param string|BaseActiveRecord $class
     * @param array $aliasProperties
     * @param array $relations
     * @param string[] $unsetAttributes
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public static function prepareArray(
        array $row,
        string $class,
        array $aliasProperties = [],
        array $relations = [],
        $unsetAttributes = ['created_at', 'created_by', 'updated_at', 'updated_by']
    ): array {
        foreach ($aliasProperties as $aliasProperty => $attribute) {
            $row[$aliasProperty] = ArrayHelper::getValue($row, $attribute);
            if (StringHelper::endsWith($attribute, '_at')) {
                $row[$aliasProperty] = $row[$aliasProperty] ? date('Y-m-d H:i:s', $row[$aliasProperty]) : '';
            } elseif (StringHelper::endsWith($attribute, '_cent')) {
                $row[$aliasProperty] /= 100;
            } elseif (StringHelper::endsWith($attribute, '_g')
                || StringHelper::endsWith($attribute, '_mm')
                || StringHelper::endsWith($attribute, '_mm2')
                || StringHelper::endsWith($attribute, '_mm3')) {
                $row[$aliasProperty] = UnitAliasBehavior::convert(
                    $row[$aliasProperty],
                    substr($attribute, strrpos($attribute, '_') + 1),
                    substr($aliasProperty, strrpos($aliasProperty, '_') + 1)
                );
            }
        }
        /**
         * @var string $relation
         * @var BaseActiveRecord $relationClass
         */
        foreach ($relations as $relation => $relationClass) {
            if (empty($row[$relation])) {
                continue;
            }
            if (ArrayHelper::isAssociative($row[$relation])) { //mean is one relation, else is many relation
                $row[$relation] = static::prepareArray($row[$relation], $relationClass);
            } else {
                foreach ($row[$relation] as $index => $value) {
                    $row[$relation][$index] = static::prepareArray($value, $relationClass);
                }
            }
        }
        $pks = $class::primaryKey();
        $pk = $pks[0];
        $row['id'] = $row[$pk];
        foreach ($unsetAttributes as $unsetAttribute) {
            unset($row[$unsetAttribute]);
        }
        return $row;
    }

    /**
     * @param BaseActiveRecord $model
     * @param array $rules
     * @param array|string[] $removeKeySuffixes
     * @return array
     * @inheritdoc
     */
    public static function formRules(
        BaseActiveRecord $model,
        array $rules,
        array $removeKeySuffixes = ['at', 'by', 'cent', 'g', 'mm', 'mm3', 'mm3', 'additional']
    ): array {
        $removeRuleAttributes = static::filterAttributes($model->attributes(), $removeKeySuffixes, false);
        $removeRuleAttributes = [$removeRuleAttributes];

        $aliasSafeRules = [];
        $aliasNumberRules = [];
        $aliasDatetimeRules = [];
        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior instanceof AliasPropertyBehavior) {
                $removeRuleAttributes[] = $behavior->aliasProperties;
                if ($behavior instanceof MoneyAliasBehavior || $behavior instanceof UnitAliasBehavior) {
                    $aliasNumberRules[] = array_keys($behavior->aliasProperties);
                } elseif ($behavior instanceof TimestampAliasBehavior) {
                    $aliasDatetimeRules[] = array_keys($behavior->aliasProperties);
                } else {
                    $aliasSafeRules[] = array_keys($behavior->aliasProperties);
                }
            } elseif ($behavior instanceof RelationSavableBehavior) {
                $aliasSafeRules[] = $behavior->relations;
            }
        }
        if ($removeRuleAttributes) {
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

    /**
     * @param BaseActiveRecord $model
     * @return array
     * @inheritdoc
     */
    public static function aliasProperties(BaseActiveRecord $model): array
    {
        $aliasProperties = [];
        foreach ($model->getBehaviors() as $behavior) {
            if ($behavior instanceof AliasPropertyBehavior) {
                $aliasProperties[] = $behavior->aliasProperties;
            }
        }
        if ($aliasProperties) {
            return array_merge(...$aliasProperties);
        }
        return [];
    }

    /**
     * @param BaseActiveRecord $model
     * @return array
     * @inheritdoc
     */
    public static function aliasFields(BaseActiveRecord $model): array
    {
        $aliasProperties = static::aliasProperties($model);
        $aliasFields = array_keys($aliasProperties);
        return array_combine($aliasFields, $aliasFields);
    }
}
