<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\relationsave;


use Yii;
use yii\base\Behavior;
use yii\base\Event;
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
    const DELETE_MODE_MODEL = 'model';
    const DELETE_MODE_SQL = 'sql';
    const SAVE_MODE_LINK = 'link';
    const SAVE_MODE_MODEL = 'model';

    /**
     * @var array
     */
    public $relations = [];
    /**
     * @var array [relationName => indexKey]
     */
    public $indexKeys = [];

    /**
     * @var array [relationName => scenariosMap]
     */
    public $scenarioMaps = [];

    /**
     * @var array [relationName => deleteMode]
     */
    public $deleteModes = [];
    /**
     * @var array [relationName => saveMode]
     */
    public $saveModes = [];

    /**
     * @var callable
     */
    public $relationFilter;

    /**
     * @var BaseActiveRecord[]|BaseActiveRecord[][]
     */
    protected $savedRelations = [];
    /**
     * @var BaseActiveRecord[]
     */
    protected $deletedRelations = [];

    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_VALIDATE => 'afterValidate',
            BaseActiveRecord::EVENT_AFTER_INSERT => 'afterSave',
            BaseActiveRecord::EVENT_AFTER_UPDATE => 'afterSave',
        ];
    }

    /**
     * Override canSetProperty method to be able to detect if a relation setter is allowed.
     * Setter is allowed if the relation is declared in the `relations` parameter
     * @param string $name
     * @param boolean $checkVars
     * @return boolean
     */
    public function canSetProperty($name, $checkVars = true)
    {
        $getter = 'get' . $name;
        if (in_array($name, $this->relations) && method_exists($this->owner, $getter) && $this->owner->$getter() instanceof ActiveQueryInterface) {
            return true;
        }
        return parent::canSetProperty($name, $checkVars);
    }

    /**
     * Override __set method to be able to set relations values either by providing a model instance,
     * a primary key value or an associative array
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        if (in_array($name, $this->relations)) {
            $indexKey = isset($this->indexKeys[$name]) ? $this->indexKeys[$name] : null;
            if (($value = $this->filterRelationData($name, $value, $indexKey)) !== null) {
                $this->setRelation($name, $value, $indexKey);
            }
        }
    }

    /**
     * @param string $name
     * @param array $data
     * @param string $indexKey
     * @return array
     */
    public function filterRelationData($name, $data, $indexKey)
    {
        if ($this->relationFilter && is_callable($this->relationFilter)) {
            return call_user_func($this->relationFilter, $name, $data, $indexKey);
        }
        return $data;
    }

    /**
     * convert relation array to model
     * @param string $name
     * @param array|BaseActiveRecord|BaseActiveRecord[] $data
     * @param null|string $indexKey
     */
    public function setRelation($name, $data, $indexKey = null)
    {
        /** @var BaseActiveRecord $owner */
        $owner = $this->owner;
        /** @var ActiveQuery $relation */
        $relation = $owner->getRelation($name);
        /** @var BaseActiveRecord $model */
        $model = new $relation->modelClass();
        if ($indexKey) {
            $data = ArrayHelper::index($data, $indexKey);
        } else {
            //@TODO need to consider multi primary key
            $primaryKeys = $model->primaryKey();
            $indexKey = $primaryKeys[0];
        }

        if ($relation->multiple) {
            /** @var BaseActiveRecord[] $oldRelations */
            $oldRelations = ArrayHelper::index($owner->$name, $indexKey);
            $tempExistModels = $this->getTempExistModels($model, $data, $indexKey, $relation);

            $models = [];
            foreach ($data as $key => $value) {
                $indexValue = ArrayHelper::getValue($value, $indexKey);
                if ($indexValue !== null && isset($oldRelations[$indexValue])) {
                    $model = ($oldRelations[$indexValue]);
                    unset($oldRelations[$indexValue]);
                } else if ($indexValue !== null && isset($tempExistModels[$indexValue])) {
                    $model = ($tempExistModels[$indexValue]);
                    unset($tempExistModels[$indexValue]);
                } else if ($value instanceof BaseActiveRecord) {
                    $model = $value;
                } else {
                    $model = new $relation->modelClass();
                }

                if (isset($this->scenarioMaps[$name][$owner->getScenario()])) {
                    $model->setScenario($this->scenarioMaps[$name][$owner->getScenario()]);
                }
                if (!($value instanceof BaseActiveRecord)) {
                    $model->load($value, '');
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
                $this->deletedRelations[$name] = [$indexKey, $oldRelations];
            }
        } else {
            /** @var BaseActiveRecord $model */
            $model = $owner->$name;
            if ($model && !$data) {
                $this->deletedRelations[$name] = [$indexKey, $model];
            } else {
                if ($data instanceof BaseActiveRecord) {
                    $model = $data;
                } else {
                    if (empty($model)) {
                        $model = new $relation->modelClass();
                    }
                    if (isset($this->scenarioMaps[$name][$owner->getScenario()])) {
                        $model->setScenario($this->scenarioMaps[$name][$owner->getScenario()]);
                    }
                    $model->load($data, '');
                }
                //just to access the validation
                foreach ($relation->link as $pk => $fk) {
                    if (!$model->$pk && !in_array($pk, $model::primaryKey())) {
                        $model->$pk = 0;
                    }
                }
                if (isset($this->scenarioMaps[$name][$owner->getScenario()])) {
                    $model->setScenario($this->scenarioMaps[$name][$owner->getScenario()]);
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
    public function getTempExistModels($model, $data, $indexKey, $relation)
    {
        if (is_string($indexKey) && $keys = array_filter(ArrayHelper::getColumn($data, $indexKey))) {
            $query = $model::find()->andWhere([$indexKey => $keys])->indexBy($indexKey);
            foreach ($relation->link as $fk => $pk) {
                $query->andWhere([$fk => 0]);
            }
            return $query->all();
        }
        return [];
    }

    /**
     * @param null|array $relationAttributeNames
     * @param bool $clearErrors
     * @return bool
     */
    public function validateRelations($relationAttributeNames = null, $clearErrors = true)
    {
        /** @var BaseActiveRecord $owner */
        $owner = $this->owner;
        foreach ($this->savedRelations as $name => $relationModels) {
            $attributeNames = isset($relationAttributeNames[$name]) ? $relationAttributeNames[$name] : null;
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
     * @return bool
     * @throws Exception
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function saveRelations()
    {
        /** @var BaseActiveRecord $owner */
        $owner = $this->owner;
        foreach ($this->savedRelations as $name => $models) {
            $relation = $owner->getRelation($name);
            if (is_array($models)) {
                foreach ($models as $model) {
                    /** @var BaseActiveRecord $model */
                    foreach ($relation->link as $fk => $pk) {
                        $model->$fk = $owner->$pk;
                    }
                    if (!$model->save(false)) {
                        $owner->addError($name, $model->getErrors() ?: 'Unknown Error');
                    }
                }
            } else {
                /** @var BaseActiveRecord $models */
                foreach ($relation->link as $pk => $fk) {
                    if ($owner->$fk && !in_array($pk, $models::primaryKey())) {
                        $models->$pk = $owner->$fk;
                    }
                }
                if (!$models->save(false)) {
                    $owner->addError($name, $models->getErrors() ?: 'Unknown Error');
                }
                foreach ($relation->link as $pk => $fk) {
                    if (!in_array($pk, $owner::primaryKey())) {
                        $owner->$fk = $models->$pk;
                    }
                }
                $changedAttributes = $owner->getDirtyAttributes();
                if ($changedAttributes) {
                    $owner->updateAttributes($changedAttributes);
                }
            }
        }

        foreach ($this->deletedRelations as $name => list($indexKey, $models)) {
            if (empty($this->deleteModes[$name]) || $this->deleteModes[$name] == static::DELETE_MODE_MODEL) {
                foreach ($models as $model) {
                    $model->delete();
                }
            } else {
                $relation = $owner->getRelation($name);
                $condition = [];
                foreach ($relation->link as $fk => $pk) {
                    $condition[$fk] = $owner->$pk;
                }
                if (is_string($indexKey)) {
                    $condition[$indexKey] = array_keys($models);
                }
                /** @var BaseActiveRecord $modelClass */
                $modelClass = $relation->modelClass;
                $modelClass::deleteAll($condition);
            }
        }

        if ($owner->hasErrors()) {
            return false;
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
        $this->deletedRelations = [];
        return true;
    }

    /**
     * @param Event $event
     * @return bool
     * @inheritdoc
     */
    public function afterValidate($event)
    {
        return $this->validateRelations();
    }

    /**
     * @throws Exception
     * @inheritdoc
     */
    public function afterSave()
    {
        /** @var BaseActiveRecord $owner */
        $owner = $this->owner;
        if (!$this->saveRelations()) {
            throw new Exception(Yii::t('app', 'Save relation model fail!'), $owner->getErrors());
        }
    }
}
