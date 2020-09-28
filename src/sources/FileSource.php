<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use creocoder\flysystem\Filesystem;
use lujie\extend\file\FileReaderInterface;
use Yii;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\helpers\FileHelper;

/**
 * Class FileSource
 * @package lujie\data\exchange\sources
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileSource extends BaseObject implements SourceInterface
{
    /**
     * @var FileReaderInterface
     */
    public $fileReader;

    /**
     * @var string
     */
    public $file = 'tmp_import.bin';

    /**
     * @var ?Filesystem
     */
    public $fs;

    /**
     * @var string
     */
    public $fsPath = 'imports';

    /**
     * @var string
     */
    public $localTmpPath = '/tmp/imports';

    /**
     * @var bool
     */
    public $unlinkTmp = true;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fileReader = Instance::ensure($this->fileReader, FileReaderInterface::class);
        if ($this->fs) {
            $this->fs = Instance::ensure($this->fs, Filesystem::class);
            $this->fsPath = rtrim($this->fsPath, "/ \t\n\r \v") . '/';
            $this->localTmpPath = rtrim(Yii::getAlias($this->localTmpPath), "/ \t\n\r \v") . '/';
        }
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function all(): array
    {
        if ($this->fs) {
            $localFilePath = $this->localTmpPath . $this->file;
            $fsFilePath = $this->fsPath . $this->file;
            $localDir = pathinfo($localFilePath, PATHINFO_DIRNAME);
            FileHelper::createDirectory($localDir);
            file_put_contents($localFilePath, $this->fs->read($fsFilePath));
            $data = $this->fileReader->read($localFilePath);
            if ($this->unlinkTmp) {
                unlink($localFilePath);
            }
        } else {
            $data = $this->fileReader->read($this->file);
        }
        return $data;
    }
}
