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
class MultipleMethodAction extends Action
{
    /**
     * @var string the scenario to be assigned to the model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string
     */
    public $method;

    /**
     * @return mixed
     * @throws ServerErrorHttpException
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @inheritdoc
     */
    public function run()
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $model = new MultipleForm([
            'scenario' => $this->scenario,
            'modelClass' => $this->modelClass,
        ]);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->{$this->method}() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to update the object for unknown reason.');
        }

        return $model->multiModels;
    }
}
