<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\controller\rest;

use lujie\project\forms\TaskAttachmentForm;
use lujie\upload\actions\UploadAction;
use lujie\upload\actions\UploadedFileDownloadAction;
use lujie\upload\forms\UploadModelFileForm;
use lujie\upload\models\UploadModelFile;
use yii\rest\Controller;

/**
 * Class UploadModelFileController
 * @package lujie\upload\controller\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadModelFileController extends Controller
{
    /**
     * @var string|UploadModelFile
     */
    public $modelClass = UploadModelFile::class;

    /**
     * @return array
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'upload' => [
                'class' => UploadAction::class,
                'modelClass' => $this->modelClass,
                'uploadModel' => [
                    'class' => UploadModelFileForm::class,
                    'model_type' => $this->modelClass::MODEL_TYPE
                ]
            ],
            'download' => [
                'class' => UploadedFileDownloadAction::class,
                'modelClass' => $this->modelClass,
            ]
        ]);
    }
}
