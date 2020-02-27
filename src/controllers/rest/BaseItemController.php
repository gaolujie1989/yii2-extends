<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\auth\controllers\rest;

use lujie\auth\forms\BaseItemForm;
use yii\base\InvalidConfigException;
use yii\di\Instance;
use yii\rbac\ManagerInterface;
use yii\rest\ActiveController;
use yii\web\NotFoundHttpException;

/**
 * Class BaseItemController
 * @package lujie\auth\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseItemController extends ActiveController
{
    /**
     * @var BaseItemForm
     */
    public $modelClass;

    /**
     * @var ManagerInterface|mixed
     */
    public $authManager = 'authManager';

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->authManager = Instance::ensure($this->authManager, ManagerInterface::class);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['index'], $actions['view']);
        foreach ($actions as $action) {
            $action['findModel'] = [$this, 'findModel'];
        }
        return $actions;
    }

    /**
     * @return array
     * @inheritdoc
     */
    abstract public function actionIndex(): array;

    /**
     * @param string $name
     * @return BaseItemForm
     * @throws NotFoundHttpException
     * @inheritdoc
     */
    protected function findModel(string $name): BaseItemForm
    {
        $itemForm = $this->modelClass::findOne($name);
        if ($itemForm === null) {
            throw new NotFoundHttpException('Item not found');
        }
        return $itemForm;
    }
}
