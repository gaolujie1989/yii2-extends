<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\relation;

use lujie\extend\helpers\ClassHelper;
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
     * @var array [relationName => indexKey]
     */
    public $indexKeys = [];

    /**
     * @var array
     */
    public $linkUnlinkRelations = [];

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
        $this->saveRelations();
        $this->deleteRelations();
    }

    /**
     * @param $name
     * @return bool
     * @inheritdoc
     */
    protected function isRelation(string $name): bool
    {
        $getter = 'get' . ucfirst($name);
        return in_array($name, $this->relations, true)
            && method_exists($this->owner, $getter)
            && $this->owner->$getter() instanceof ActiveQueryInterface;
    }

    /**
     * @param string $name
     * @param bool $checkVars
     * @return bool
     * @inheritdoc
     */
    public function canSetProperty($name, $checkVars = true): bool
    {
        if ($this->isRelation($name)) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
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
        if ($this->isRelation($name)) {
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
     * @param $data
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function setRelation(string $name, $data): void
    {
        $owner = $this->owner;
        /** @var ActiveQuery $relation */
        $relation = $owner->getRelation($name);
        /** @var BaseActiveRecord $model */
        $model = new $relation->modelClass();

        $indexKey = $this->indexKeys[$name] ?? null;
        if ($indexKey) {
            //for filter duplication
            $data = ArrayHelper::index($data, $indexKey);
        } else {
            $primaryKeys = $model::primaryKey();
            if (count($primaryKeys) === 1) {
                $indexKey = $primaryKeys[0];
            } else if (count($primaryKeys) > 1) {
                $indexKey = static function($v) use ($primaryKeys) {
                    $pkValues = array_intersect_key($v, array_flip($primaryKeys));
                    return implode('_', $pkValues);
                };
            } else {
                throw new InvalidConfigException('Model must have a primaryKey');
            }
        }

        if ($relation->multiple) {
            /** @var BaseActiveRecord[] $oldRelations */
            $oldRelations = ArrayHelper::index($owner->$name, $indexKey);
            $unlinkModels = [];
            if (isset($this->indexKeys[$name])
                && is_string($this->indexKeys[$name])
                && in_array($name, $this->linkUnlinkRelations, true)) {
                $unlinkModels = $this->getUnlinkModels($model, $data, $indexKey, $relation);
            }

            $models = [];
            foreach ($data as $key => $values) {
                $indexValue = ArrayHelper::getValue($values, $indexKey);
                if ($indexValue !== null && isset($oldRelations[$indexValue])) {
                    $model = $oldRelations[$indexValue];
                    unset($oldRelations[$indexValue]);
                } else if ($indexValue !== null && isset($unlinkModels[$indexValue])) {
                    $model = $unlinkModels[$indexValue];
                    unset($unlinkModels[$indexValue]);
                } else if ($values instanceof BaseActiveRecord) {
                    $model = $values;
                } else {
                    $model = new $relation->modelClass();
                }

                if (isset($this->scenarioMaps[$name][$owner->getScenario()])) {
                    $model->setScenario($this->scenarioMaps[$name][$owner->getScenario()]);
                }
                if (!($values instanceof BaseActiveRecord)) {
                    $model->setAttributes($values);
                }
                //just to access the validation
                foreach ($relation->link as $fk => $pk) {
                    if (!$model->$fk) {
                        $model->$fk = 0;
                    }
                }
                $models[$key] = $model;
            }
            $this->savedRelations[$name] = $models;
            if ($oldRelations) {
                $this->deletedRelations[$name] = $oldRelations;
            }
        } else {
            /** @var BaseActiveRecord $model */
            $model = $owner->$name;
            if ($model && !$data) {
                $this->deletedRelations[$name] = $model;
            } else {
                if ($data instanceof BaseActiveRecord) {
                    $model = $data;
                } else if ($model === null) {
                    $model = new $relation->modelClass();
                }
                if (isset($this->scenarioMaps[$name][$owner->getScenario()])) {
                    $model->setScenario($this->scenarioMaps[$name][$owner->getScenario()]);
                }
                if (!($data instanceof BaseActiveRecord)) {
                    $model->setAttributes($data);
                }

                //just to access the validation
                foreach ($relation->link as $pk => $fk) {
                    if (!$model->$pk && !in_array($pk, $model::primaryKey(), true)) {
                        $model->$pk = 0;
                    }
                }
                $this->savedRelations[$name] = $model;
            }
        }
    }

    /**
     * @param BaseActiveRecord $model
     * @param array $data
     * @param string $indexKey
     * @param ActiveQuery $relation
     * @return array
     * @inheritdoc
     */
    public function getUnlinkModels(BaseActiveRecord $model, array $data, string $indexKey, ActiveQuery $relation): array
    {
        if ($indexValues = array_filter(ArrayHelper::getColumn($data, $indexKey))) {
            $query = $model::find()->andWhere([$indexKey => $indexValues])->indexBy($indexKey);
            foreach ($relation->link as $fk => $pk) {
                $query->andWhere([$fk => 0]);
            }
            return $query->all();
        }
        return [];
    }

    /**
     * @param array|null $relationAttributeNames
     * @param bool $clearErrors
     * @return bool
     * @inheritdoc
     */
    public function validateRelations(?array $relationAttributeNames = null, bool $clearErrors = true): bool
    {
        /** @var BaseActiveRecord $owner */
        $owner = $this->owner;
        foreach ($this->savedRelations as $name => $relationModels) {
            $attributeNames = $relationAttributeNames[$name] ?? null;
            if (is_array($relationModels)) {
                $errors = [];
                foreach ($relationModels as $key => $model) {
                    if (!$model->validate($attributeNames, $clearErrors)) {
                        $errors[$key] = $model->getErrors();
                    }
                }
                if ($errors) {
                    $owner->addError($name, $errors);
                }
            } else {
                $model = $relationModels;
                if (!$model->validate($attributeNames, $clearErrors)) {
                    $owner->addError($name, $model->getErrors());
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
            } else if ($saveMode === static::SAVE_MODE_LINK) {
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
            if ($relation->multiple && $relation->indexBy !== null) {
                $models = ArrayHelper::index($models, $relation->indexBy);
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
            } else if ($deleteMode === static::DELETE_MODE_SQL) {
                /** @var BaseActiveRecord $modelClass */
                $modelClass = $relation->modelClass;
                $condition = ['AND'];
                $pks = $modelClass::primaryKey();
                if ($pks) {
                    foreach ($pks as $pk) {
                        $condition[] = [$pk => ArrayHelper::getColumn($models, $pk)];
                    }
                } else if (isset($this->indexKeys[$name]) && is_string($this->indexKeys[$name])) {
                    $indexKey = $this->indexKeys[$name];
                    $condition[] = [$indexKey => ArrayHelper::getColumn($models, $indexKey)];
                    foreach ($relation->link as $from => $to) {
                        $condition[] = [$from => $owner->getAttribute($to)];
                    }
                } else {
                    throw new InvalidConfigException('Model must have a primaryKey');
                }
                $modelClass::deleteAll($condition);
            } else if ($deleteMode === static::DELETE_MODE_UNLINK) {
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
     * @param $models
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
            foreach ($models as $model) {
                /** @var BaseActiveRecord $model */
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
