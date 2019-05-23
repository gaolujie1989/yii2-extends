<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange;

use lujie\data\exchange\pipelines\FilePipeline;
use lujie\data\exchange\sources\SourceInterface;
use yii\base\InvalidConfigException;

/**
 * Class FileExporter
 *
 * @property FilePipeline $pipeline
 *
 * @package lujie\data\exchange
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class FileExporter extends DataDataExchanger
{
    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (!($this->pipeline instanceof FilePipeline)) {
            throw new InvalidConfigException('File exporter pipeline must be instanceof FilePipeline');
        }
    }

    public function exportToFile(SourceInterface $source, string $file): bool
    {
        $this->pipeline->filePathTemplate = $file;
        return $this->exchange($source);
    }
}
