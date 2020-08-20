<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\flysystem\controllers\rest;

use lujie\upload\actions\FsFileDownloadAction;
use lujie\upload\actions\UploadAction;
use lujie\upload\forms\UploadForm;
use yii\rest\Controller;

/**
 * Class FsFileController
 * @package lujie\flysystem\controllers\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FsFileController extends Controller
{
    /**
     * @var string
     */
    public $fileModelType;

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
                'checkAccess' => [$this, 'checkAccess'],
                'uploadModel' => [
                    'class' => UploadForm::class,
                    'fs' => 'filesystem'
                ]
            ],
            'download' => [
                'class' => FsFileDownloadAction::class,
                'checkAccess' => [$this, 'checkAccess'],
            ]
        ]);
    }
}
