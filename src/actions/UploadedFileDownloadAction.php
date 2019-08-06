<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\actions;

use lujie\upload\models\UploadSavedFile;
use Yii;
use yii\rest\Action;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

/**
 * Class UploadedFileViewAction
 * @package lujie\upload
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadedFileDownloadAction extends Action
{
    /**
     * @var UploadSavedFile
     */
    public $modelClass;

    /**
     * if tmp dir is set, storage file in tmp
     * config http web server to tmp folder that if file exists it will not run the action but get file directly
     * @var string
     */
    public $tmp = '/tmp/';

    /**
     * @param $file
     * @throws NotFoundHttpException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     * @inheritdoc
     */
    public function run($id): void
    {
        /** @var UploadSavedFile $uploadSavedFile */
        $uploadSavedFile = $this->findModel($id);

        if ($this->tmp) {
            $tmpFilePath = $this->tmp . $uploadSavedFile->file;
            if (!file_exists($tmpFilePath)) {
                file_put_contents($tmpFilePath, $uploadSavedFile->getContent());
            }
            Yii::$app->getResponse()->xSendFile($tmpFilePath, $uploadSavedFile->name);
        } else {
            Yii::$app->getResponse()->sendContentAsFile(
                $uploadSavedFile->getContent(),
                $uploadSavedFile->name,
                ['mimeType' => FileHelper::getMimeTypeByExtension($uploadSavedFile->file)]
            );
        }
    }
}
