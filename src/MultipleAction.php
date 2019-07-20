<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\batch;

use Yii;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class MultiSaveAction
 * @package lujie\import\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MultipleAction extends Action
{
    /**
     * @var string
     */
    public $method = 'save';

    /**
     * @return MultipleForm
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function run(): MultipleForm
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $model = new MultipleForm([
            'modelClass' => $this->modelClass,
        ]);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->{$this->method}() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to save the object for unknown reason.');
        }

        return $model;
    }
}
