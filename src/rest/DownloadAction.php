<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\rest;

use lujie\extend\flysystem\Filesystem;
use Yii;
use yii\base\InvalidArgumentException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii\rest\Action;
use yii\web\Response;

/**
 * Class DownloadAction
 * @package lujie\extend\rest
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DownloadAction extends Action
{
    public const SEND_TYPE_FILE = 'FILE';
    public const SEND_TYPE_FILE_X = 'FILE_X';
    public const SEND_TYPE_CONTENT = 'CONTENT';
    public const SEND_TYPE_STREAM = 'STREAM';
    public const SEND_TYPE_CDN = 'CDN';

    /**
     * @var Filesystem
     */
    public $fs = 'filesystem';

    /**
     * @var string
     */
    public $fileAttribute = 'file';

    /**
     * @var string
     */
    public $fileNameAttribute;

    /**
     * @var callable
     */
    public $fileNameCallback;

    /**
     * @var array
     */
    public $options = ['inline' => true];

    /**
     * @var string
     */
    public $tmp = '@runtime/tmp';

    /**
     * @var string
     */
    public $sendType = self::SEND_TYPE_STREAM;

    /**
     * @var string header name
     */
    public $xHeader = 'X-Sendfile';

    /**
     * @param $id
     * @return Response
     * @throws \League\Flysystem\FilesystemException
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\web\NotFoundHttpException
     * @throws \yii\web\RangeNotSatisfiableHttpException
     * @inheritdoc
     */
    public function run($id): Response
    {
        /** @var BaseActiveRecord $model */
        $model = $this->findModel($id);

        if ($this->checkAccess) {
            call_user_func($this->checkAccess, $this->id, $model);
        }

        $this->fs = Instance::ensure($this->fs, Filesystem::class);
        $filePath = $model->getAttribute($this->fileAttribute);

        if (is_callable($this->fileNameCallback)) {
            $fileName = call_user_func($this->fileNameCallback, $model);
        } else if ($this->fileAttribute) {
            $fileName = $model->getAttribute($this->fileNameAttribute);
        } else {
            $fileName = pathinfo($filePath, PATHINFO_BASENAME);
        }

        $options = $this->options;
        $options['mimeType'] = FileHelper::getMimeTypeByExtension($filePath);

        /** @var Response $response */
        $response = Yii::$app->getResponse();
        switch ($this->sendType) {
            case self::SEND_TYPE_FILE:
            case self::SEND_TYPE_FILE_X:
                $tmp = Yii::getAlias($this->tmp);
                FileHelper::createDirectory($tmp);
                $tmpFile = $tmp . DIRECTORY_SEPARATOR . $fileName;
                file_put_contents($tmpFile, $this->fs->read($filePath));
                if ($this->sendType === self::SEND_TYPE_FILE) {
                    return $response->sendFile($tmpFile, $fileName, $options);
                }
                $options['xHeader'] = $this->xHeader;
                return $response->xSendFile($tmpFile, $fileName, $options);
            case self::SEND_TYPE_STREAM:
                return $response->sendStreamAsFile($this->fs->readStream($filePath), $fileName, $options);
            case self::SEND_TYPE_CONTENT:
                return $response->sendStreamAsFile($this->fs->read($filePath), $fileName, $options);
            case self::SEND_TYPE_CDN:
                return $response->redirect($this->fs->publicUrl($filePath));
            default:
                throw new InvalidArgumentException('Invalid send type.');
        }
    }
}
