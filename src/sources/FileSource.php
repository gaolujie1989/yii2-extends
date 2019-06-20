<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\sources;

use creocoder\flysystem\Filesystem;
use lujie\data\exchange\file\FileParserInterface;
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
     * @var FileParserInterface
     */
    public $fileParser;

    /**
     * @var string
     */
    public $file = 'tmp_import.bin';

    /**
     * @var Filesystem
     */
    public $fs;

    /**
     * @var string
     */
    public $fsPath = 'imports';

    /**
     * @var string
     */
    public $localPath = '/tmp/imports';

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
        $this->fileParser = Instance::ensure($this->fileParser, FileParserInterface::class);
        $this->localPath = rtrim(Yii::getAlias($this->localPath), "/ \t\n\r \v") . '/';
        if ($this->fs) {
            $this->fs = Instance::ensure($this->fs, Filesystem::class);
            $this->fsPath = rtrim($this->fsPath, "/ \t\n\r \v") . '/';
        }
    }

    /**
     * @return array
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function all(): array
    {
        $localFilePath = $this->localPath . $this->file;
        if ($this->fs) {
            $fsFilePath = $this->fsPath . $this->file;
            $localDir = pathinfo($localFilePath, PATHINFO_DIRNAME);
            FileHelper::createDirectory($localDir);
            file_put_contents($localFilePath, $this->fs->read($fsFilePath));
        }
        $data = $this->fileParser->parseFile($localFilePath);
        if ($this->unlinkTmp) {
            unlink($localFilePath);
        }
        return $data;
    }
}
