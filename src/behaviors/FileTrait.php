<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\upload\behaviors;

use creocoder\flysystem\Filesystem;
use Yii;
use yii\di\Instance;
use yii\helpers\FileHelper;
use yii\web\UploadedFile;

/**
 * Trait FileBehaviorTrait
 *
 * @property Filesystem|null $fs
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
    public function initFsAndPath(): void
    {
        if ($this->fs) {
            $this->fs = Instance::ensure($this->fs, Filesystem::class);
            if (strpos($this->path, '@') === 0 || strpos($this->path, '/') === 0) {
                $this->path = substr($this->path, 1);
                $pos = strpos($this->path, '/');
                $this->path = $pos !== false ? substr($this->path, $pos + 1) : '';
            }
        } else {
            $this->path = Yii::getAlias($this->path);
            if (!file_exists($this->path)) {
                FileHelper::createDirectory($this->path, 0777);
            }
        }
        if ($this->path) {
            $this->path = rtrim($this->path, '/') . '/';
        } else {
            $this->path = '';
        }
    }

    /**
     * @param string $filePath
     * @param UploadedFile $file
     * @param bool $deleteTempFile
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function saveUploadedFile(string $filePath, UploadedFile $file, bool $deleteTempFile = true): bool
    {
        if ($file->error !== UPLOAD_ERR_OK || !is_uploaded_file($file->tempName)) {
            return false;
        }

        if ($this->fs) {
            $result = $this->saveFile($filePath, $file->tempName, $deleteTempFile);
        } else {
            $path = $this->path . $filePath;
            $dir = pathinfo($path, PATHINFO_DIRNAME);
            if (!file_exists($dir)) {
                FileHelper::createDirectory($this->path, 0777);
            }
            $result = $file->saveAs($path, $deleteTempFile);
        }
        return $result;
    }

    #region file actions

    /**
     * @param string $filePath
     * @return bool
     * @inheritdoc
     */
    public function existFile(string $filePath): bool
    {
        $path = $this->path . $filePath;
        return $this->fs ? $this->fs->has($path) : file_exists($path);
    }

    /**
     * @param string $filePath
     * @param string $source
     * @param bool $deleteFile
     * @return bool
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function saveFile(string $filePath, string $source, bool $deleteFile = false): bool
    {
        $path = $this->path . $filePath;
        if ($this->fs) {
            if (($fp = fopen($source, 'rb')) === false) {
                return false;
            }
            $result = $this->fs->writeStream($path, $fp);
            fclose($fp);
            if ($result && $deleteFile) {
                unlink($source);
            }
        } else {
            $dir = pathinfo($path, PATHINFO_DIRNAME);
            if (!file_exists($dir)) {
                FileHelper::createDirectory($this->path, 0777);
            }
            $result = $deleteFile ? rename($source, $path) : copy($source, $path);
        }
        return $result;
    }

    /**
     * @param string $filePath
     * @return bool
     * @inheritdoc
     */
    public function deleteFile(string $filePath): bool
    {
        $path = $this->path . $filePath;
        if ($this->fs) {
            if ($this->fs->has($path)) {
                return $this->fs->delete($path);
            }
        } else if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    /**
     * @param string $filePath
     * @return false|string|null
     * @inheritdoc
     */
    public function read(string $filePath)
    {
        $path = $this->path . $filePath;
        if ($this->fs) {
            if ($this->fs->has($path)) {
                return $this->fs->read($path);
            }
        } else if (file_exists($path)) {
            return file_get_contents($path);
        }
        return null;
    }

    /**
     * @param string $filePath
     * @return false|resource|null
     * @inheritdoc
     */
    public function readStream(string $filePath)
    {
        $path = $this->path . $filePath;
        if ($this->fs) {
            if ($this->fs->has($path)) {
                return $this->fs->readStream($path);
            }
        } else if (file_exists($path)) {
            return fopen($path, 'rb');
        }
        return null;
    }

    #endregion
}
