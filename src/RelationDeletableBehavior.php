<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\ar\relation\behaviors;

use lujie\extend\helpers\ClassHelper;
use yii\base\Behavior;
use yii\base\InvalidConfigException;
use yii\db\BaseActiveRecord;
use yii\db\Exception;

/**
 * Class RelationsBehavior
 *
 * @property BaseActiveRecord $owner
 *
 * @package lujie\relationsave\db
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class RelationDeletableBehavior extends Behavior
{
    public const DELETE_MODE_MODEL = 'MODEL';
    public const DELETE_MODE_SQL = 'SQL';
    public const DELETE_MODE_UNLINK = 'UNLINK';

    /**
     * @var array
     */
    public $relations = [];

    /**
     * @var array [relationName => deleteMode]
     */
    public $deleteModes = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function events(): array
    {
        return [
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function afterDelete(): void
    {
        $this->deleteRelations();
    }

    /**
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function deleteRelations(): void
    {
        foreach ($this->relations as $name) {
            $deleteMode = $this->deleteModes[$name] ?? static::DELETE_MODE_MODEL;
            if ($deleteMode === static::DELETE_MODE_MODEL) {
                $this->deleteRelationByModel($name);
            } else if ($deleteMode === static::DELETE_MODE_SQL) {
                $this->deleteRelationBySql($name);
            } else if ($deleteMode === static::DELETE_MODE_UNLINK) {
                $this->owner->unlinkAll($name, true);
            } else {
                throw new InvalidConfigException('Invalid delete mode');
            }
        }
    }

    /**
     * @param string $name
     * @throws Exception
     * @throws InvalidConfigException
     * @throws \yii\db\StaleObjectException
     * @inheritdoc
     */
    public function deleteRelationByModel(string $name): void
    {
        $owner = $this->owner;
        $relation = $owner->getRelation($name);
        if ($relation->via !== null) {
            throw new InvalidConfigException('DELETE_MODE_MODEL not support for relation has via');
        }
        /** @var BaseActiveRecord[] $relationModels */
        $relationModels = $owner->$name;
        if (!$relation->multiple) {
            if (empty($relationModels)) {
                return;
            }
            $relationModels = [$relationModels];
        }
        foreach ($relationModels as $model) {
            if (!$model->delete()) {
                $message = strtr('Delete relation {modelClass}: {modelId} failed.', [
                    '{modelClass}' => ClassHelper::getClassShortName($model),
                    '{modelId}' => $model->getPrimaryKey(),
                ]);
                throw new Exception($message, $model->getErrors());
            }
        }
    }

    /**
     * @param string $name
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function deleteRelationBySql(string $name): void
    {
        $owner = $this->owner;
        $relation = $owner->getRelation($name);
        $condition = [];
        foreach ($relation->link as $from => $to) {
            $condition[$from] = $owner->getAttribute($to);
        }
        /** @var BaseActiveRecord $modelClass */
        $modelClass = $relation->modelClass;
        $modelClass::deleteAll($condition);
    }
}
