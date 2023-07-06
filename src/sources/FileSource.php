<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use lujie\extend\flysystem\Filesystem;
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
        $this->localTmpPath = rtrim(Yii::getAlias($this->localTmpPath), "/ \t\n\r \v") . '/';
        if ($this->fs) {
            $this->fs = Instance::ensure($this->fs, Filesystem::class);
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
            $fileName = pathinfo($this->file, PATHINFO_BASENAME);
            $localFilePath = $this->localTmpPath . $fileName;
            $localDir = pathinfo($localFilePath, PATHINFO_DIRNAME);
            FileHelper::createDirectory($localDir);
            file_put_contents($localFilePath, $this->fs->read($this->file));
            try {
                return $this->fileReader->read($localFilePath);
            } finally {
                if ($this->unlinkTmp) {
                    unlink($localFilePath);
                }
            }
        }

        return $this->fileReader->read($this->file);
    }

    /**
     * @return int
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function count(): int
    {
        return count($this->all());
    }
}
