<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload;

use lujie\upload\models\UploadSavedFile;
use Yii;
use yii\base\Action;
use yii\helpers\FileHelper;
use yii\web\NotFoundHttpException;

/**
 * Class UploadedFileViewAction
 * @package lujie\upload
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadedFileViewAction extends Action
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
    public function run($file)
    {
        /** @var UploadSavedFile $uploadedFile */
        $uploadedFile = $this->modelClass::find()->file($file)->one();
        if (!$uploadedFile) {
            throw new NotFoundHttpException('File not found.');
        }

        if ($this->tmp) {
            $tmpFilePath = $this->tmp . $uploadedFile->file;
            if (!file_exists($tmpFilePath)) {
                file_put_contents($tmpFilePath, $uploadedFile->getFileContent());
            }
            Yii::$app->getResponse()->xSendFile($tmpFilePath, $uploadedFile->name);
        } else {
            Yii::$app->getResponse()->sendContentAsFile(
                $uploadedFile->getFileContent(),
                $uploadedFile->name,
                ['mimeType' => FileHelper::getMimeTypeByExtension($uploadedFile->file)]
            );
        }
    }
}
