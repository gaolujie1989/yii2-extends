<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use lujie\data\exchange\file\FileExporterInterface;
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
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->fileExporter = Instance::ensure($this->fileExporter, FileExporterInterface::class);
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        $file = $this->getFilePath();
        $dir = pathinfo($file, PATHINFO_DIRNAME);
        FileHelper::createDirectory($dir);
        $this->fileExporter->exportToFile($file, $data);
    }

    /**
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    public function getFilePath(): string
    {
        return strtr($this->filePathTemplate, [
            '{date}' => date('ymd'),
            '{datetime}' => date('ymdHis'),
            '{rand}' => random_int(1000, 9999)
        ]);
    }
}
