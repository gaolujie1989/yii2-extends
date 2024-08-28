<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\actions;

use lujie\upload\models\UploadModelFile;
use Yii;
use yii\helpers\FileHelper;
use yii\rest\Action;
use yii\web\NotFoundHttpException;

/**
 * Class UploadedFileViewAction
 * @package lujie\upload
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class UploadedFileDownloadAction extends Action
{
    /**
     * @var string|UploadModelFile
 */
    public $modelClass = UploadModelFile::class;

    /**
     * if tmp dir is set, storage file in tmp
     * config http web server to tmp folder that if file exists it will not run the action but get file directly
     * @var string
     */
    public $tmp = '/tmp/';

    /**
     * @var array
     */
    public $options = ['inline' => true];

    /**
     * @var array
     */
    public $allowedModelTypes = [];

    /**
     * @param int|string $id
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     * @throws \yii\web\RangeNotSatisfiableHttpException
     * @inheritdoc
     */
    public function run($id): void
    {
        /** @var UploadModelFile $uploadSavedFile */
        $uploadSavedFile = $this->findModel($id);
        if ($this->allowedModelTypes && !in_array($uploadSavedFile->model_type, $this->allowedModelTypes, true)) {
            throw new NotFoundHttpException("File not allowed: $id");
        }

        $content = $uploadSavedFile->getContent();
        if (empty($content)) {
            throw new NotFoundHttpException("File is empty: $id");
        }
        if ($this->tmp) {
            $tmpFilePath = Yii::getAlias($this->tmp) . $uploadSavedFile->file;
            if (!file_exists($tmpFilePath) || empty(file_get_contents($tmpFilePath))) {
                FileHelper::createDirectory(dirname($tmpFilePath));
                file_put_contents($tmpFilePath, $content);
            }
            Yii::$app->getResponse()->sendFile($tmpFilePath, $uploadSavedFile->name, $this->options);
        } else {
            $mimeTypeByExtension = FileHelper::getMimeTypeByExtension($uploadSavedFile->file);
            Yii::$app->getResponse()->sendContentAsFile(
                $content,
                $uploadSavedFile->name,
                array_merge($this->options, ['mimeType' => $mimeTypeByExtension])
            );
        }
    }
}
