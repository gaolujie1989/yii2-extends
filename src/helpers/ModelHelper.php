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
use Yii;
use yii\db\ActiveQuery;
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

    #region rule

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

    #endregion

    #region find

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

    #endregion

    #region query and search

    public static $FILTER_KEY_SUFFIXES = [
        'FILTER' => [
            'id', 'type', 'group', 'status',
            'country', 'currency', 'language',
            'carrier', 'departure', 'destination',
        ],
        'BOTH_LIKE' => ['no', 'key', 'code'],
        'LEFT_LIKE' => [],
        'RIGHT_LIKE' => [],
        'LIKE' => ['name', 'title'],
        'DATETIME_RANGE' => ['at', 'date', 'time'],
        'RANGE' => [],
    ];

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
     * @param array $filterKeySuffixes
     * @return ActiveQueryInterface
     * @inheritdoc
     */
    public static function query(
        BaseActiveRecord $model,
        ActiveQueryInterface $query = null,
        string $alias = '',
        array $filterKeySuffixes = []
    ): ActiveQueryInterface
    {
        $defaultFilterKeySuffixes = static::$FILTER_KEY_SUFFIXES;
        foreach ($defaultFilterKeySuffixes as $key => $keySuffixes) {
            $defaultFilterKeySuffixes[$key] = array_combine($keySuffixes, $keySuffixes);
        }
        $filterKeySuffixes = ArrayHelper::merge($defaultFilterKeySuffixes, $filterKeySuffixes);
        $filterKeySuffixes = array_filter(array_map('array_filter', $filterKeySuffixes));
        $filterKeyAttributes = [];
        foreach ($filterKeySuffixes as $key => $keySuffixes) {
            $filterKeyAttributes[$key] = static::filterAttributes($model->attributes(), $keySuffixes, false);
        }
        $query = $query ?: $model::find();
        if ($alias && $query instanceof ActiveQuery) {
            $query->alias($alias);
        }
        foreach ($filterKeyAttributes as $key => $filterAttributes) {
            switch ($key) {
                case 'FILTER':
                    QueryHelper::filterValue($query, $model->getAttributes($filterAttributes), false, $alias);
                    break;
                case 'LEFT_LIKE':
                    QueryHelper::filterValue($query, $model->getAttributes($filterAttributes), 'L', $alias);
                    break;
                case 'RIGHT_LIKE':
                    QueryHelper::filterValue($query, $model->getAttributes($filterAttributes), 'R', $alias);
                    break;
                case 'BOTH_LIKE':
                    QueryHelper::filterValue($query, $model->getAttributes($filterAttributes), 'LR', $alias);
                    break;
                case 'LIKE':
                    QueryHelper::filterValue($query, $model->getAttributes($filterAttributes), true, $alias);
                    break;
                case 'DATETIME_RANGE':
                case 'RANGE':
                    QueryHelper::filterRange($query, $model->getAttributes($filterAttributes), $alias);
                    break;
            }
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
    public static function searchRules(BaseActiveRecord $model, array $filterKeySuffixes = [], array $datetimeKeySuffixes = []): array
    {
        $defaultFilterKeySuffixes = array_merge(...array_values(static::$FILTER_KEY_SUFFIXES));
        $defaultDateTimeKeySuffixes = static::$FILTER_KEY_SUFFIXES['DATETIME_RANGE'] ?? [];
        $defaultFilterKeySuffixes = array_combine($defaultFilterKeySuffixes, $defaultFilterKeySuffixes);
        $defaultDateTimeKeySuffixes = array_combine($defaultDateTimeKeySuffixes, $defaultDateTimeKeySuffixes);

        $filterKeySuffixes = array_merge($defaultFilterKeySuffixes, $filterKeySuffixes);
        $datetimeKeySuffixes = array_merge($defaultDateTimeKeySuffixes, $datetimeKeySuffixes);
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
     * @param array $unsetAttributes
     * @return array
     * @throws \Exception
     * @inheritdoc
     */
    public static function prepareArray(
        array $row,
        string $class,
        array $aliasProperties = [],
        array $relations = [],
        array $unsetAttributes = ['created_by', 'updated_by']
    ): array
    {
        if (empty(Yii::$app->params['prepareArray'][$class])) {
            $model = new $class();
            $modelAliasProperties = static::aliasProperties($model);
            $extraRelations = static::extraRelations($model);
            $excludeFields = static::excludeFields($model);
            Yii::$app->params['prepareArray'][$class] = [$modelAliasProperties, $extraRelations, $excludeFields];
        }
        [$modelAliasProperties, $extraRelations, $excludeFields] = Yii::$app->params['prepareArray'][$class];
        $aliasProperties = array_merge($modelAliasProperties, $aliasProperties);
        $relations = array_merge($extraRelations, $relations);

        $row = ActiveDataHelper::typecast($row, $class);
        //prepare relations
        /**
         * @var string $relation
         * @var BaseActiveRecord|string $relationClass
         */
        foreach ($relations as $relation => $relationConfig) {
            if (empty($row[$relation])) {
                continue;
            }
            $relationConfig = (array)$relationConfig;
            $relationClass = $relationConfig[0];
            $relationAlias = $relationConfig[1] ?? [];
            $relationRelations = $relationConfig[2] ?? [];
            $relationPk = $relationClass::primaryKey()[0];
            if (isset($row[$relation][$relationPk])) { //mean is one relation, else is many relation
                $row[$relation] = static::prepareArray($row[$relation], $relationClass, $relationAlias, $relationRelations);
            } else {
                foreach ($row[$relation] as $index => $value) {
                    $row[$relation][$index] = static::prepareArray($value, $relationClass, $relationAlias, $relationRelations);
                }
            }
        }
        //prepare alias
        foreach ($aliasProperties as $aliasProperty => $attribute) {
            $aliasRawValue = ArrayHelper::getValue($row, $attribute);
            if (is_numeric($aliasRawValue)) {
                if (StringHelper::endsWith($attribute, '_at')) {
                    $row[$aliasProperty] = $aliasRawValue ? date('Y-m-d H:i:s', $aliasRawValue) : '';
                } elseif (StringHelper::endsWith($attribute, '_micro_cent')) {
                    $row[$aliasProperty] = number_format($aliasRawValue / 10000, 4, '.', '');
                } elseif (StringHelper::endsWith($attribute, '_cent')) {
                    $row[$aliasProperty] = number_format($aliasRawValue / 100, 2, '.', '');
                } elseif (StringHelper::endsWith($attribute, '_g')
                    || StringHelper::endsWith($attribute, '_mm')
                    || StringHelper::endsWith($attribute, '_mm2')
                    || StringHelper::endsWith($attribute, '_mm3')) {
                    $row[$aliasProperty] = UnitAliasBehavior::convert(
                        $aliasRawValue,
                        substr($attribute, strrpos($attribute, '_') + 1),
                        substr($aliasProperty, strrpos($aliasProperty, '_') + 1)
                    );
                }
            }
        }
        //prepare pk id
        $pks = $class::primaryKey();
        $pk = $pks[0];
        $row['id'] = $row[$pk];
        foreach ($unsetAttributes as $unsetAttribute) {
            unset($row[$unsetAttribute]);
        }
        foreach ($excludeFields as $excludeField) {
            unset($row[$excludeField]);
        }
        return $row;
    }

    #endregion

    #region form

    /**
     * @param BaseActiveRecord $model
     * @param array $rules
     * @param array|string[] $removeKeySuffixes
     * @param array $skipBehaviors
     * @return array
     * @inheritdoc
     */
    public static function formRules(BaseActiveRecord $model, array $rules, array $removeKeySuffixes = [], array $skipBehaviors = []): array
    {
        $defaultRemoveKeySuffixes = ['at', 'by', 'cent', 'g', 'mm', 'mm3', 'mm3', 'options', 'additional'];
        $defaultRemoveKeySuffixes = array_combine($defaultRemoveKeySuffixes, $defaultRemoveKeySuffixes);

        $removeKeySuffixes = array_filter(array_merge($defaultRemoveKeySuffixes, $removeKeySuffixes));
        $removeRuleAttributes = static::filterAttributes($model->attributes(), $removeKeySuffixes, false);
        $removeRuleAttributes = [$removeRuleAttributes];

        $aliasSafeRules = [];
        $aliasNumberRules = [];
        $aliasDatetimeRules = [];
        $aliasDefaultRules = [];
        foreach ($model->getBehaviors() as $behaviorName => $behavior) {
            if (in_array($behaviorName, $skipBehaviors, true)) {
                continue;
            }
            if ($behavior instanceof AliasPropertyBehavior) {
                $removeRuleAttributes[] = $behavior->aliasProperties;
                foreach ($behavior->aliasDefaults as $aliasProperty => $defaultValue) {
                    $aliasDefaultRules[$defaultValue][] = $aliasProperty;
                }
                if ($behavior instanceof MoneyAliasBehavior || $behavior instanceof UnitAliasBehavior) {
                    $aliasNumberRules[] = array_keys($behavior->aliasProperties);
                    $aliasDefaultRules[0] = array_merge($aliasDefaultRules[0] ?? [], array_keys($behavior->aliasProperties));
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
        if ($aliasDefaultRules) {
            foreach ($aliasDefaultRules as $defaultValue => $aliasProperties) {
                $rules[] = [$aliasProperties, 'default', 'value' => $defaultValue];
            }
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

    #endregion

    #region fields and extraFields

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

    /**
     * @param BaseActiveRecord $model
     * @return array
     * @inheritdoc
     */
    public static function extraRelations(BaseActiveRecord $model): array
    {
        $relations = $model->extraFields();
        foreach ($relations as $key => $relation) {
            $getRelation = 'get' . ucfirst($relation);
            if (!$model->hasMethod($getRelation)) {
                unset($relations[$key]);
            } else {
                $relationQuery = $model->{$getRelation}();
                if ($relationQuery instanceof ActiveQuery) {
                    $relations[$key] = $relationQuery->modelClass;
                } else {
                    unset($relations[$key]);
                }
            }
        }
        return $relations;
    }

    /**
     * @param BaseActiveRecord $model
     * @return array
     * @inheritdoc
     */
    public static function excludeFields(BaseActiveRecord $model): array
    {
        $model->setAttributes(array_flip($model->attributes()), false);
        $fields = $model->fields();
        $attributes = $model->attributes();
        return array_diff($attributes, $fields);
    }

    #endregion
}
