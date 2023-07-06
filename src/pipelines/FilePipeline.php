<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use lujie\extend\flysystem\Filesystem;
use lujie\extend\file\FileWriterInterface;
use Yii;
use yii\base\BaseObject;
use yii\di\Instance;
use yii\helpers\FileHelper;

/**
 * Class FileDbPipeline
 * @package lujie\data\exchange\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FilePipeline extends BaseObject implements PipelineInterface
{
    /**
     * @var FileWriterInterface
     */
    public $fileWriter;

    /**
     * @var string
     */
    public $filePathTemplate = '/tmp/exports/{date}/tmp_{datetime}.bin';

    /**
     * @var ?Filesystem
     */
    public $fs;

    /**
     * @var string
     */
    public $fsPath = 'exports';

    /**
     * @var string
     */
    public $localPath = '/tmp/exports';

    /**
     * @var string
     */
    private $filePath = '';

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
        $this->fileWriter = Instance::ensure($this->fileWriter, FileWriterInterface::class);
        $this->localPath = rtrim(Yii::getAlias($this->localPath), "/ \t\n\r \v") . '/';
        if ($this->fs) {
            $this->fs = Instance::ensure($this->fs, Filesystem::class);
            $this->fsPath = rtrim($this->fsPath, "/ \t\n\r \v") . '/';
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        $this->filePath = $this->generateFilePath();
        $localFilePath = $this->localPath . $this->filePath;
        $localDir = pathinfo($localFilePath, PATHINFO_DIRNAME);
        FileHelper::createDirectory($localDir);
        $this->fileWriter->write($localFilePath, $data);
        if ($this->fs) {
            $fsFilePath = $this->fsPath . $this->filePath;
            $this->fs->write($fsFilePath, file_get_contents($localFilePath));
            if ($this->unlinkTmp) {
                unlink($localFilePath);
            }
        }
        return true;
    }

    /**
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    protected function generateFilePath(): string
    {
        return strtr($this->filePathTemplate, [
            '{date}' => date('ymd'),
            '{datetime}' => date('ymdHis'),
            '{rand}' => random_int(1000, 9999)
        ]);
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getFilePath(): string
    {
        if ($this->fs) {
            return $this->fsPath . $this->filePath;
        }
        return $this->localPath . $this->filePath;
    }
}
