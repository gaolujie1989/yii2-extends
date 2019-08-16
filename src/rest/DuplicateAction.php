<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use yii\db\ActiveRecord;
use yii\db\BaseActiveRecord;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class CloneAction
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DuplicateAction extends Action
{
    /**
     * @var array
     */
    public $with = [];

    /**
     * @param $id
     * @return BaseActiveRecord
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function run($id): BaseActiveRecord
    {
        /* @var $model ActiveRecord */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        /** @var ActiveRecord $newModel */
        $newModel = new $this->modelClass();
        $newModel->setAttributes($model->getAttributes(null, $model::primaryKey()), false);

        foreach ($this->with as $with) {
            /** @var ActiveRecord|ActiveRecord[] $modelRelations */
            $modelRelations = $model->{$with};
            if (is_array($modelRelations)) {
                foreach ($modelRelations as $modelRelation) {
                    $modelRelation->setIsNewRecord(true);
                    $modelRelation->setAttributes(array_fill_keys($modelRelation::primaryKey(), null));
                }
            } else {
                $modelRelations->setIsNewRecord(true);
                $modelRelations->setAttributes(array_fill_keys($modelRelations::primaryKey(), null));
            }
            $newModel->{$with} = $modelRelations;
        }

        if ($model->save() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to clone the object for unknown reason.');
        }

        return $model;
    }
}
