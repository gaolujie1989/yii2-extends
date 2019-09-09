<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\file\writers\ExcelWriter;
use lujie\data\exchange\pipelines\FilePipeline;
use yii\base\InvalidConfigException;

/**
 * Class FileExporter
 *
 * @property FilePipeline $pipeline
 *
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileExporter extends DataExchanger
{
    public $pipeline = [
        'class' => FilePipeline::class,
        'fileWriter' => ExcelWriter::class,
    ];

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!($this->pipeline instanceof FilePipeline)) {
            throw new InvalidConfigException('File exporter pipeline must be instanceof FilePipeline');
        }
    }

    /**
     * @param string $file
     * @return bool
     * @throws InvalidConfigException
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function export(string $file): bool
    {
        $this->prepare($file);
        return $this->execute();
    }

    /**
     * @param string $file
     * @inheritdoc
     */
    public function prepare(string $file): void
    {
        $this->pipeline->filePathTemplate = $file;
    }
}
