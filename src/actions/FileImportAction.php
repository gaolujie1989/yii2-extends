<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\data\exchange\actions;

use lujie\data\exchange\forms\FileImportForm;
use Yii;
use yii\base\Model;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class FileImportAction
 * @package lujie\data\exchange\actions
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileImportAction extends Action
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string|array
     */
    public $importModel;

    /**
     * @return FileImportForm
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function run(): FileImportForm
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var FileImportForm $model */
        $model = Yii::createObject($this->importModel);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->import() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to import for unknown reason.');
        }
        return $model;
    }
}
