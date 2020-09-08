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

/**
 * Class UploadModelFileController
 * @package lujie\upload\controller\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadModelFileController extends ActiveController
{
    /**
     * @var string|UploadModelFile
     */
    public $modelClass = UploadModelFile::class;

    /**
     * @var string
     */
    public $fileModelType;

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
                    'model_type' => $this->fileModelType ?: $this->modelClass::MODEL_TYPE,
                    'allowedModelTypes' => $this->allowedModelTypes
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
}
