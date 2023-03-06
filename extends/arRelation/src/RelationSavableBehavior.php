<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\relation\behaviors;

use lujie\extend\helpers\ClassHelper;
use lujie\extend\helpers\ValueHelper;
use yii\base\Behavior;
use yii\base\InvalidCallException;
use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveQueryInterface;
use yii\db\BaseActiveRecord;
use yii\db\Exception;
use yii\helpers\ArrayHelper;

/**
 * Class RelationsBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\relationsave\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RelationSavableBehavior extends Behavior
{
    public const SAVE_MODE_MODEL = 'MODEL';
    public const SAVE_MODE_LINK = 'LINK';
    public const DELETE_MODE_MODEL = 'MODEL';
    public const DELETE_MODE_SQL = 'SQL';
    public const DELETE_MODE_UNLINK = 'UNLINK';

    /**
     * @var array
     */
    public $relations = [];

    /**
     * for link many
     * @var array ['alias' => [relationName, 'attribute']
     */
    public $relationAttributeAlias = [];

    /**
     * @var array [relationName => indexKey]
     */
    public $indexKeys = [];

    /**
     * @var array
     */
    public $linkUnlinkRelations = [];

    /**
     * @var array
     */
    public $relationFilters;

    /**
     * @var array [relationName => scenariosMap]
     */
    public $scenarioMaps = [];

    /**
     * @var array [relationName => saveMode]
     */
    public $saveModes = [];

    /**
     * @var array [relationName => saveMode]
     */
    public $deleteModes = [];

    /**
     * @var BaseActiveRecord[]|BaseActiveRecord[][]
     */
    protected $savedRelations = [];

    /**
     * @var BaseActiveRecord[]|BaseActiveRecord[][]
     */
    protected $deletedRelations = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    /**
     * @inheritdoc
     */
    public function afterValidate(): void
    {
        $this->validateRelations();
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function afterSave(): void
    {
        $this->deleteRelations();
        $this->saveRelations();
    }

    /**
     * @param string $name
     * @return bool
     * @inheritdoc
     */
    protected function isRelation(string $name): bool
    {
        $getter = 'get' . ucfirst($name);
        return in_array($name, $this->relations, true)
            && $this->owner->hasMethod($getter)
            && $this->owner->$getter() instanceof ActiveQueryInterface;
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canGetProperty($name, $checkVars = true): bool
    {
        if (isset($this->relationAttributeAlias[$name])) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true): bool
    {
        if (isset($this->relationAttributeAlias[$name])) {
            return true;
        }
        if ($this->isRelation($name)) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    /**
     * @param string $name
     * @return mixed
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __get($name)
    {
        if (isset($this->relationAttributeAlias[$name])) {
            [$relation, $attribute] = $this->relationAttributeAlias[$name];
            $relationModels = $this->owner->{$relation};
            return ArrayHelper::getColumn((array)$relationModels, $attribute);
        }
        return parent::__get($name);
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws InvalidConfigException
     * @throws \yii\base\UnknownPropertyException
     * @inheritdoc
     */
    public function __set($name, $value)
    {
        if (isset($this->relationAttributeAlias[$name])) {
            [$relation, $attribute] = $this->relationAttributeAlias[$name];
            $value = array_map(static function($v) use ($attribute) {
                return [$attribute => $v];
            }, (array)$value);
            $this->setRelation($relation, $value);
        } else if ($this->isRelation($name)) {
            $this->setRelation($name, $value);
        } else {
            parent::__set($name, $value);
        }
    }

    #region relation action set/validate/save

    /**
     * convert array data to models
     * for not multi relations, should set link attribute first, then set relation data,
     * because it will load one relation model first and check, then set with relation
     * @param string $name
     * @param array|BaseActiveRecord $data
     * @throws InvalidConfigException
     * @throws \Exception
     * @inheritdoc
     */
    public function setRelation(string $name, $data): void
    {
        $relationFilter = $this->relationFilters[$name] ?? null;
        if ($relationFilter && is_callable($relationFilter)) {
            $data = $relationFilter($data);
        }
        $owner = $this->owner;
        /** @var ActiveQuery $relation */
        $relation = $owner->getRelation($name);
        /** @var BaseActiveRecord $model */
        $model = new $relation->modelClass();

        $indexKey = $this->indexKeys[$name] ?? null;
        if ($indexKey) {
            if (is_array($indexKey)) {
                if (count($indexKey) === 1) {
                    $indexKey = reset($indexKey);
                } else {
                    $indexKey = static function ($values) use ($indexKey) {
                        ValueHelper::getIndexValues($values, $indexKey);
                    };
                }
            }
            //for filter duplication
            $data = ArrayHelper::index($data, $indexKey);
        } else {
            $primaryKeys = $model::primaryKey();
            if (count($primaryKeys) === 1) {
                $indexKey = $primaryKeys[0];
            } elseif (count($primaryKeys) > 1) {
                $indexKey = static function ($values) use ($primaryKeys) {
                    ValueHelper::getIndexValues($values, $primaryKeys);
                };
            } else {
                throw new InvalidConfigException('Model must have a primaryKey');
            }
        }

        if ($relation->multiple) {
            /** @var BaseActiveRecord[] $oldRelations */
            $oldRelations = ArrayHelper::index($owner->$name, $indexKey);
            $unlinkModels = [];
            if (isset($this->indexKeys[$name]) && in_array($name, $this->linkUnlinkRelations, true)) {
                $unlinkModels = $this->getUnlinkModels($model, $data, $this->indexKeys[$name], $relation);
                $unlinkModels = ArrayHelper::index($unlinkModels, $indexKey);
            }

            $models = [];
            foreach ($data as $key => $values) {
                $indexValue = ArrayHelper::getValue($values, $indexKey);
                if ($indexValue !== null && isset($oldRelations[$indexValue])) {
                    $model = $oldRelations[$indexValue];
                    unset($oldRelations[$indexValue]);
                } elseif ($indexValue !== null && isset($unlinkModels[$indexValue])) {
                    $model = $unlinkModels[$indexValue];
                    unset($unlinkModels[$indexValue]);
                } elseif ($values instanceof BaseActiveRecord) {
                    $model = $values;
                } else {
                    $model = new $relation->modelClass();
                }
                if (isset($this->scenarioMaps[$name][$owner->getScenario()])) {
                    $model->setScenario($this->scenarioMaps[$name][$owner->getScenario()]);
                }
                //just to access the validation
                foreach ($relation->link as $fk => $pk) {
                    if (!$model->$fk) {
                        $model->$fk = $owner->$pk ?: 0;
                    }
                }
                if ($values instanceof BaseActiveRecord) {
                    $model->setAttributes($values->getAttributes());
                } else {
                    $model->setAttributes($values);
                }
                $models[$key] = $model;
            }
            $this->savedRelations[$name] = $models;
            if ($oldRelations) {
                $this->deletedRelations[$name] = $oldRelations;
            }
        } else {
            /** @var ?BaseActiveRecord $model */
            $model = $owner->$name;
            if ($model && empty($data)) {
                $this->deletedRelations[$name] = [$model];
            } else {
                if ($data instanceof BaseActiveRecord) {
                    $model = $data;
                } elseif ($model === null) {
                    $model = new $relation->modelClass();
                }
                if (isset($this->scenarioMaps[$name][$owner->getScenario()])) {
                    $model->setScenario($this->scenarioMaps[$name][$owner->getScenario()]);
                }
                //just to access the validation
                foreach ($relation->link as $pk => $fk) {
                    if (!$model->$pk && !in_array($pk, $model::primaryKey(), true)) {
                        $model->$pk = $owner->$fk ?: 0;
                    }
                }
                if (!($data instanceof BaseActiveRecord)) {
                    $model->setAttributes($data);
                }
                $this->savedRelations[$name] = $model;
            }
        }
    }

    /**
     * @param BaseActiveRecord $model
     * @param array $data
     * @param string|array $indexKey
     * @param ActiveQuery $relation
     * @return array
     * @inheritdoc
     */
    public function getUnlinkModels(BaseActiveRecord $model, array $data, $indexKey, ActiveQuery $relation): array
    {
        $query = $model::find();
        if (is_array($indexKey) && count($indexKey) === 1) {
            $indexKey = reset($indexKey);
        }
        if (is_string($indexKey)) {
            if ($indexValues = array_filter(ArrayHelper::getColumn($data, $indexKey))) {
                $query->andWhere([$indexKey => $indexValues]);
            } else {
                return [];
            }
        } else if (is_array($indexKey)) {
            $indexKeyFlip = array_flip($indexKey);
            $condition = ['OR'];
            foreach ($data as $values) {
                $conditionValues = array_intersect_key($values, $indexKeyFlip);
                if (array_filter($conditionValues)) {
                    $condition[] = $conditionValues;
                }
            }
            if (count($condition) > 1) {
                $query->andWhere($condition);
            } else {
                return [];
            }
        } else {
            return [];
        }

        foreach ($relation->link as $fk => $pk) {
            $query->andWhere([$fk => 0]);
        }
        return $query->all();
    }

    /**
     * @param array|null $relationAttributeNames
     * @param bool $clearErrors
     * @return bool
     * @inheritdoc
     */
    public function validateRelations(?array $relationAttributeNames = null, bool $clearErrors = true): bool
    {
        $owner = $this->owner;
        if ($owner->hasErrors()) {
            return false;
        }
        foreach ($this->savedRelations as $name => $relationModels) {
            $attributeNames = $relationAttributeNames[$name] ?? null;
            if (is_array($relationModels)) {
                $errors = [];
                foreach ($relationModels as $key => $model) {
                    if (!$model->validate($attributeNames, $clearErrors)) {
                        $errors[$key] = $model->getFirstErrors();
                    }
                }
                if ($errors) {
                    $owner->addError($name, $errors);
                }
            } else {
                $model = $relationModels;
                if (!$model->validate($attributeNames, $clearErrors)) {
                    $owner->addError($name, $model->getFirstErrors());
                }
            }
        }
        return !$owner->hasErrors();
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function saveRelations(): void
    {
        $owner = $this->owner;
        foreach ($this->savedRelations as $name => $models) {
            $saveMode = $this->saveModes[$name] ?? static::SAVE_MODE_MODEL;
            if ($saveMode === static::SAVE_MODE_MODEL) {
                $this->saveRelationByModel($name, $models);
            } elseif ($saveMode === static::SAVE_MODE_LINK) {
                $relation = $owner->getRelation($name);
                if (!is_array($models)) {
                    $models = [$models];
                }
                foreach ($models as $model) {
                    if (!$relation->multiple) {
                        throw new InvalidCallException('SAVE_MODE_LINK not support for one relation');
                    }
                    $owner->link($name, $model);
                }
            } else {
                throw new InvalidConfigException('Invalid save mode');
            }
        }
        foreach ($this->savedRelations as $name => $models) {
            $relation = $owner->getRelation($name);
            // update lazily loaded related objects
            if ($relation->multiple) {
                if ($relation->indexBy === null) {
                    $models = array_values($models);
                } else {
                    $models = ArrayHelper::index($models, $relation->indexBy);
                }
            }
            $owner->populateRelation($name, $models);
        }
        $this->savedRelations = [];
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function deleteRelations(): void
    {
        $owner = $this->owner;
        foreach ($this->deletedRelations as $name => $models) {
            $deleteMode = $this->deleteModes[$name] ?? static::DELETE_MODE_MODEL;
            $relation = $owner->getRelation($name);
            if ($deleteMode === static::DELETE_MODE_MODEL) {
                if ($relation->via !== null) {
                    throw new InvalidConfigException('DELETE_MODE_MODEL not support for relation has via');
                }
                foreach ($models as $model) {
                    if (!$model->delete()) {
                        $message = strtr('Delete relation {modelClass}: {modelId} failed.', [
                            '{modelClass}' => ClassHelper::getClassShortName($model),
                            '{modelId}' => $model->getPrimaryKey(),
                        ]);
                        throw new Exception($message, $model->getErrors());
                    }
                }
            } elseif ($deleteMode === static::DELETE_MODE_SQL) {
                /** @var BaseActiveRecord $modelClass */
                $modelClass = $relation->modelClass;
                $condition = ['AND'];
                $pks = $modelClass::primaryKey();
                if ($pks) {
                    foreach ($pks as $pk) {
                        $condition[] = [$pk => ArrayHelper::getColumn($models, $pk)];
                    }
                } elseif (isset($this->indexKeys[$name]) && is_string($this->indexKeys[$name])) {
                    $indexKey = $this->indexKeys[$name];
                    $condition[] = [$indexKey => ArrayHelper::getColumn($models, $indexKey)];
                    foreach ($relation->link as $from => $to) {
                        $condition[] = [$from => $owner->getAttribute($to)];
                    }
                } else {
                    throw new InvalidConfigException('Model must have a primaryKey');
                }
                $modelClass::deleteAll($condition);
            } elseif ($deleteMode === static::DELETE_MODE_UNLINK) {
                foreach ($models as $model) {
                    $delete = $relation->multiple;
                    $owner->unlink($name, $model, $delete);
                }
            } else {
                throw new InvalidConfigException('Invalid delete mode');
            }
        }
        $this->deletedRelations = [];
    }

    /**
     * @param string $name
     * @param array|BaseActiveRecord $models
     * @throws Exception
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function saveRelationByModel(string $name, $models): void
    {
        $owner = $this->owner;
        $relation = $owner->getRelation($name);
        if ($relation->via !== null) {
            throw new InvalidConfigException('DELETE_MODE_MODEL not support for relation has via');
        }
        if (is_array($models)) {
            /** @var BaseActiveRecord $model */
            foreach ($models as $model) {
                foreach ($relation->link as $fk => $pk) {
                    $model->$fk = $owner->$pk;
                }
                if (!$model->save(false)) {
                    $message = strtr('Save relation {modelClass} failed.', [
                        '{modelClass}' => ClassHelper::getClassShortName($relation->modelClass),
                    ]);
                    throw new Exception($message, $model->getErrors());
                }
            }
        } else {
            /** @var BaseActiveRecord $model */
            $model = $models;
            foreach ($relation->link as $pk => $fk) {
                if ($owner->$fk && !in_array($pk, $model::primaryKey(), true)) {
                    $model->$pk = $owner->$fk;
                }
            }
            if (!$model->save(false)) {
                $message = strtr('Save relation {modelClass} failed.', [
                    '{modelClass}' => ClassHelper::getClassShortName($relation->modelClass),
                ]);
                throw new Exception($message, $model->getErrors());
            }

            foreach ($relation->link as $pk => $fk) {
                if (!in_array($pk, $owner::primaryKey(), true)) {
                    $owner->$fk = $models->$pk;
                }
            }
            $changedAttributes = $owner->getDirtyAttributes();
            if ($changedAttributes) {
                $owner->updateAttributes($changedAttributes);
            }
        }
    }

    #endregion
}
