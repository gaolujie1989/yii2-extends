<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\import;

use Yii;
use yii\base\Model;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class UploadAction
 * @package lujie\data\exchange\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ImportAction extends Action
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
     * @return ImportForm
     * @throws ServerErrorHttpException
     * @throws \Throwable
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function run(): ImportForm
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        /** @var ImportForm $model */
        $model = Yii::createObject($this->importModel);
        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model->import() === false && !$model->hasErrors()) {
            throw new ServerErrorHttpException('Failed to import for unknown reason.');
        }
        return $model;
    }
}
