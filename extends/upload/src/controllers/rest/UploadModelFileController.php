<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\controllers\rest;

use lujie\extend\rest\ActiveController;
use lujie\upload\actions\UploadAction;
use lujie\upload\actions\UploadedFileDownloadAction;
use lujie\upload\forms\UploadModelFileForm;
use lujie\upload\models\UploadModelFile;
use Yii;

/**
 * Class UploadModelFileController
 * @package lujie\upload\controller\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadModelFileController extends ActiveController
{
    /**
     * @var string
     */
    public $modelClass = UploadModelFile::class;

    /**
     * @var string
     */
    public $formClass = UploadModelFile::class;

    /**
     * @var array
     */
    public $allowedModelTypes = [];

    /**
     * @return array
     * @inheritdoc
     */
    public function actions(): array
    {
        $actions = parent::actions();
        unset($actions['create'], $actions['update']);
        return array_merge($actions, [
            'upload' => [
                'class' => UploadAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'uploadModel' => [
                    'class' => UploadModelFileForm::class,
                    'allowedModelTypes' => $this->allowedModelTypes,
                    'model_type' => $this->modelClass::MODEL_TYPE,
                ]
            ],
            'download' => [
                'class' => UploadedFileDownloadAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'allowedModelTypes' => $this->allowedModelTypes,
            ]
        ]);
    }

    /**
     * @param string $fileModelType
     * @inheritdoc
     */
    public function setFileModelType(string $fileModelType): void
    {
        Yii::warning('The property `fileModelType` is deprecated.', __METHOD__);
        $this->allowedModelTypes = [$fileModelType];
    }
}
