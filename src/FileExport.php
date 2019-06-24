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
class FileExport extends DataExchange
{
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
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    public function export(string $file): bool
    {
        $this->pipeline->filePathTemplate = $file;
        return $this->execute();
    }
}
