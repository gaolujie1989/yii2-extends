<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\project\controllers\backend;

use lujie\extend\rest\ActiveController;
use lujie\project\forms\TaskAttachmentForm;
use lujie\project\models\TaskAttachment;
use lujie\upload\actions\UploadAction;
use lujie\upload\actions\UploadedFileDownloadAction;

/**
 * Class TaskAttachmentController
 * @package lujie\project\controllers\backend
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TaskAttachmentController extends ActiveController
{
    public $modelClass = TaskAttachment::class;

    /**
     * @return array
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function actions(): array
    {
        return array_merge(parent::actions(), [
            'upload' => [
                'class' => UploadAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
                'uploadModel' => [
                    'class' => TaskAttachmentForm::class
                ]
            ],
            'download' => [
                'class' => UploadedFileDownloadAction::class,
                'modelClass' => $this->modelClass,
                'checkAccess' => [$this, 'checkAccess'],
            ]
        ]);
    }
}
