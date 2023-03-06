<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\rest;

use Yii;
use yii\base\Exception;
use yii\base\UserException;
use yii\db\BaseActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * Class DeleteAction
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DeleteAction extends \yii\rest\DeleteAction
{
    /**
     * @param mixed $id
     * @throws Exception
     * @throws ServerErrorHttpException
     * @throws \yii\db\Exception
     * @throws \yii\db\StaleObjectException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function run($id): void
    {
        /** @var BaseActiveRecord $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        if ($model->delete() === false) {
            if ($model->hasErrors()) {
                throw new UserException(implode(';', $model->getErrorSummary(true)));
            }
            throw new ServerErrorHttpException('Failed to delete the object for unknown reason.');
        }

        Yii::$app->getResponse()->setStatusCode(204);
    }
}
