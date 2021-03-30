<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\upload\actions;

use lujie\upload\forms\UploadForm;
use lujie\upload\models\UploadModelFile;
use Yii;
use yii\base\InvalidArgumentException;
use yii\base\InvalidConfigException;
use yii\base\Model;
use yii\db\BaseActiveRecord;
use yii\rest\Action;
use yii\web\ServerErrorHttpException;

/**
 * Class UploadAction
 * @package lujie\uploadImport\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadAction extends Action
{
    /**
     * @var string the scenario to be assigned to the new model before it is validated and saved.
     */
    public $scenario = Model::SCENARIO_DEFAULT;

    /**
     * @var string|array
     */
    public $uploadModel = [
        'class' => UploadForm::class,
    ];

    /**
     * @var array
     */
    public $allowedModelTypes = [];

    /**
     * @param string $modelTypeKey
     * @return UploadForm|mixed|object|BaseActiveRecord
     * @throws InvalidConfigException
     * @throws ServerErrorHttpException
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function run(string $modelTypeKey = '')
    {
        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id);
        }

        $modelClass = $this->uploadModel ?: $this->modelClass;
        $model = Yii::createObject($modelClass);
        $model->setScenario($this->scenario);

        $model->load(Yii::$app->getRequest()->getBodyParams(), '');
        if ($model instanceof UploadForm) {
            if ($model->saveUploadedFile() === false && !$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to save upload file for unknown reason.');
            }
        } elseif ($model instanceof BaseActiveRecord) {
            if ($model instanceof UploadModelFile && $modelTypeKey) {
                if (empty($this->allowedModelTypes[$modelTypeKey])) {
                    throw new InvalidArgumentException("Invalid model type of {$modelTypeKey}, not allowed");
                }
                $model->model_type = $this->allowedModelTypes[$modelTypeKey];
            }
            if ($model->save() === false && !$model->hasErrors()) {
                throw new ServerErrorHttpException('Failed to save the upload object for unknown reason.');
            }
        } else {
            throw new InvalidConfigException('Model class should be UploadForm or BaseActiveRecord');
        }

        return $model;
    }
}
