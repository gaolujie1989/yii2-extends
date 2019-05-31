<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use creocoder\flysystem\Filesystem;
use lujie\data\exchange\file\FileExporterInterface;
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
     * @var FileExporterInterface
     */
    public $fileExporter;

    /**
     * @var string
     */
    public $filePathTemplate = '/tmp/exports/{date}/tmp_{datetime}.bin';

    /**
     * @var Filesystem
     */
    public $fs;

    /**
     * @var
     */
    public $localPath = '/tmp/';

    /**
     * @var string
     */
    private $filePath = '';

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fileExporter = Instance::ensure($this->fileExporter, FileExporterInterface::class);
        $this->localPath = Yii::getAlias($this->localPath);
        if ($this->fs) {
            $this->fs = Instance::ensure($this->fs);
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
        $localPath = $this->localPath . $this->filePath;
        $localDir = pathinfo($localPath, PATHINFO_DIRNAME);
        FileHelper::createDirectory($localDir);
        $this->fileExporter->exportToFile($localPath, $data);
        if ($this->fs) {
            $this->fs->write($this->filePath, file_get_contents($localPath));
            unlink($localPath);
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
        return $this->filePath;
    }
}
