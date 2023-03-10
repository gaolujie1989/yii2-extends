<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\rest;

use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\rest\Action;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

/**
 * Class MethodAction
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MethodAction extends Action
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var array
     */
    public $modelConfig = [];

    /**
     * @var string
     */
    public $method;

    /**
     * @var array
     */
    public $params = [];

    /**
     * @var bool
     */
    public $requireId = false;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();

        /** @var Model $model */
        $model = new $this->modelClass();
        if (!$this->method || !$model->hasMethod($this->method)) {
            throw new InvalidConfigException('Method not exists');
        }
    }

    /**
     * @param null|int|string $id
     * @return Model
     * @throws InvalidConfigException
     * @throws NotFoundHttpException
     * @throws ServerErrorHttpException
     * @inheritdoc
     */
    public function run($id = null): Model
    {
        if ($this->requireId && empty($id)) {
            throw new InvalidArgumentException('Id must be set');
        }
        /* @var $model Model */
        $model = $this->requireId ? $this->findModel($id) : new $this->modelClass($this->modelConfig);
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $model->scenario = $this->scenario;
        $requestParams = Yii::$app->getRequest()->getBodyParams();
        if (empty($requestParams)) {
            $requestParams = Yii::$app->getRequest()->getQueryParams();
        }
        $model->load($requestParams, '');
        $result = call_user_func_array([$model, $this->method], $this->params);
        if ($result === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to execute method for unknown reason.');
        }
        return $model;
    }
}
