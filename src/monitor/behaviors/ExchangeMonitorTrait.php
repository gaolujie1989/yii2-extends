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
        $data = [];
        if ($executable instanceof FileImporter) {
            $data['importFile'] = $executable->source->file;
            if ($executable->pipeline instanceof DbPipelineInterface) {
                $data['importRowCounts'] = $executable->pipeline->getAffectedRowCounts();
            }
        } else if ($executable instanceof FileExporter) {
            $data['exportFile'] = $executable->pipeline->getFilePath();
            if ($executable->source instanceof QuerySource) {
                $data['exportConditions'] = json_encode($executable->source->query->where);
            }
        }
        return $data;
    }
}
