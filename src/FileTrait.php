<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload;

use creocoder\flysystem\Filesystem;
use Yii;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Trait FileBehaviorTrait
 *
 * @property Filesystem $fs
 * @property string $path
 *
 * @package lujie\uploadImport\behaviors
 */
trait FileTrait
{
    /**
     * @throws \yii\base\Exception
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function initFsAndPath()
    {
        if ($this->fs) {
            $this->fs = Instance::ensure($this->fs);
            $pos = strpos($this->path, '/');
            $this->path = $pos !== false ? substr($this->path, $pos + 1) : '';
        } else {
            $this->path = Yii::getAlias($this->path);
            if (!file_exists($this->path)) {
                FileHelper::createDirectory($this->path, 0777);
            }
        }
        if ($this->path) {
            $this->path = rtrim($this->path, '/') . '/';
        }
    }

    /**
     * @param $fileName
     * @param UploadedFile $file
     * @param bool $deleteTempFile
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function saveUploadedFile($fileName, UploadedFile $file, $deleteTempFile = true)
    {
        $filePath = $this->path . $fileName;
        if ($this->fs) {
            $result = $this->fs->writeStream($filePath, fopen($file->tempName, 'r'));
            if ($deleteTempFile) {
                unlink($file->tempName);
            }
        } else {
            $fileDir = pathinfo($filePath, PATHINFO_DIRNAME);
            if (!file_exists($fileDir)) {
                FileHelper::createDirectory($this->path, 0777);
            }
            $result = $file->saveAs($filePath, $deleteTempFile);
        }
        return $result;
    }

    /**
     * @param $fileName
     * @param $file
     * @param bool $deleteFile
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function saveFile($fileName, $file, $deleteFile = false)
    {
        $filePath = $this->path . $fileName;
        if ($this->fs) {
            $result = $this->fs->writeStream($filePath, fopen($file, 'r'));
            if ($deleteFile) {
                unlink($file);
            }
        } else {
            $fileDir = pathinfo($filePath, PATHINFO_DIRNAME);
            if (!file_exists($fileDir)) {
                FileHelper::createDirectory($this->path, 0777);
            }
            $result = $deleteFile ? rename($file, $filePath) : copy($file, $filePath);
        }
        return $result;
    }

    /**
     * @param $fileName
     * @return bool
     * @inheritdoc
     */
    public function deleteFile($fileName)
    {
        $filePath = $this->path . $fileName;
        if ($this->fs) {
            if ($this->fs->has($filePath)) {
                return $this->fs->delete($filePath);
            }
        } else {
            if (file_exists($filePath)) {
                return unlink($filePath);
            }
        }
        return false;
    }

    /**
     * @param $fileName
     * @return bool|false|string
     * @inheritdoc
     */
    public function loadFile($fileName)
    {
        $filePath = $this->path . $fileName;
        if ($this->fs) {
            if ($this->fs->has($filePath)) {
                return $this->fs->read($filePath);
            }
        } else {
            if (file_exists($filePath)) {
                return file_get_contents($filePath);
            }
        }
        return false;
    }
}
