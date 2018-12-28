<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\relationsave;


use yii\base\Behavior;
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
    const DELETE_MODE_MODEL = 'model';
    const DELETE_MODE_SQL = 'sql';

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
    public function events()
    {
        return [
            BaseActiveRecord::EVENT_AFTER_DELETE => 'afterDelete',
        ];
    }

    /**
     * @inheritdoc
     */
    public function deleteRelations()
    {
        $owner = $this->owner;
        foreach ($this->relations as $name) {
            if (empty($this->deleteModes[$name]) || $this->deleteModes[$name] == static::DELETE_MODE_MODEL) {
                /** @var BaseActiveRecord|BaseActiveRecord[] $relationModels */
                $relationModels = $owner->$name;
                if (is_array($relationModels)) {
                    foreach ($relationModels as $model) {
                        if (!$model->delete() && $model->hasErrors()) {
                            throw new Exception("Can not delete {get_class($model)} {$model->getPrimaryKey()}", $model->getErrors());
                        }
                    }
                } else if ($model = $relationModels) {
                    if (!$model->delete() && $model->hasErrors()) {
                        throw new Exception("Can not delete {get_class($model)} {$model->getPrimaryKey()}", $model->getErrors());
                    }
                }
            }
        }
    }

    /**
     * @throws Exception
     * @inheritdoc
     */
    public function afterDelete()
    {
        $this->deleteRelations();
    }
}