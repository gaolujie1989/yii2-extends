<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\actions;

use creocoder\flysystem\Filesystem;
use Yii;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii\rest\Action;
use yii\web\NotFoundHttpException;

/**
 * Class FsFileDownloadAction
 * @package lujie\upload\actions
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FsFileDownloadAction extends Action
{
    /**
     * @var Filesystem
     */
    public $fs = 'filesystem';

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
     * @param string $path
     * @throws NotFoundHttpException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     * @inheritdoc
     */
    public function actionDownload($path)
    {
        $path = base64_decode($path);
        /** @var Filesystem $fileSystem */
        $this->fs = Instance::ensure($this->fs);
        if (!$this->fs->has($path)) {
            throw new NotFoundHttpException("File {$path} Not Found");
        }

        $attachmentName = pathinfo($path, PATHINFO_BASENAME);
        if ($this->tmp) {
            $tmpFilePath = Yii::getAlias($this->tmp) . $path;
            if (!file_exists($tmpFilePath)) {
                FileHelper::createDirectory(dirname($tmpFilePath));
                file_put_contents($tmpFilePath, $this->fs->read($path));
            }
            Yii::$app->getResponse()->sendFile($tmpFilePath, $attachmentName, $this->options);
        } else {
            $mimeTypeByExtension = FileHelper::getMimeTypeByExtension($path);
            //php7.4 bug, fread(): read of 8192 bytes failed with errno=21 Is a directory, stream read on closed stream
            Yii::$app->getResponse()->sendContentAsFile(
                $this->fs->read($path),
                $attachmentName,
                array_merge($this->options, ['mimeType' => $mimeTypeByExtension])
            );
        }
    }
}
