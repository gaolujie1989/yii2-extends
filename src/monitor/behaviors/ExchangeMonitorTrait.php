<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\monitor\behaviors;

use lujie\data\exchange\FileExporter;
use lujie\data\exchange\FileImporter;
use lujie\data\exchange\pipelines\DbPipelineInterface;
use lujie\data\exchange\sources\QuerySource;
use lujie\executing\ExecutableInterface;

trait ExchangeMonitorTrait
{
    /**
     * @param ExecutableInterface $executable
     * @return array
     * @inheritdoc
     */
    public function getExchangeAdditional(ExecutableInterface $executable): array
    {
        $additional = [];
        if ($executable instanceof FileImporter) {
            $additional['importFile'] = $executable->source->file;
            if ($executable->pipeline instanceof DbPipelineInterface) {
                $additional['importRowCounts'] = $executable->pipeline->getAffectedRowCounts();
            }
        } else if ($executable instanceof FileExporter) {
            $additional['exportFile'] = $executable->pipeline->getFilePath();
            if ($executable->source instanceof QuerySource) {
                $additional['exportConditions'] = json_encode($executable->source->query->where);
            }
        }
        return $additional;
    }
}
