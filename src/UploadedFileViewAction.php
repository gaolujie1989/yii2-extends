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
    public function run($file): void
    {
        /** @var UploadSavedFile $savedFile */
        $savedFile = $this->modelClass::find()->file($file)->one();
        if (!$savedFile) {
            throw new NotFoundHttpException('File not found.');
        }

        if ($this->tmp) {
            $tmpFilePath = $this->tmp . $savedFile->file;
            if (!file_exists($tmpFilePath)) {
                file_put_contents($tmpFilePath, $savedFile->getContent());
            }
            Yii::$app->getResponse()->xSendFile($tmpFilePath, $savedFile->name);
        } else {
            Yii::$app->getResponse()->sendContentAsFile(
                $savedFile->getContent(),
                $savedFile->name,
                ['mimeType' => FileHelper::getMimeTypeByExtension($savedFile->file)]
            );
        }
    }
}
