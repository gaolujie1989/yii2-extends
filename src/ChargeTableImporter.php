<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging;

use lujie\charging\models\ChargeTable;
use lujie\charging\transformers\ChargeTableImportTransformer;
use lujie\data\exchange\FileImporter;
use lujie\data\exchange\pipelines\DbPipeline;

/**
 * Class ChargeTableImporter
 * @package lujie\charging
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class ChargeTableImporter extends FileImporter
{
    /**
     * @var string[]
     */
    public $transformer = ChargeTableImportTransformer::class;

    /**
     * @var string[]
     */
    public $pipeline = [
        'class' => DbPipeline::class,
        'modelClass' => ChargeTable::class,
    ];
}