<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\importers;

use lujie\data\exchange\DataExchanger;
use lujie\data\exchange\pipelines\DbPipeline;
use lujie\data\exchange\transformers\TransformerInterface;
use lujie\sales\channel\models\OttoBrand;

/**
 * Class OttoCategoryImporter
 * @package lujie\sales\channel\importers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoBrandImporter extends DataExchanger implements TransformerInterface
{
    /**
     * @var array
     */
    public $pipeline = [
        'class' => DbPipeline::class,
        'modelClass' => OttoBrand::class,
        'indexKeys' => ['key'],
    ];

    /**
     * @param array $data
     * @return array
     * @inheritdoc
     */
    public function transform(array $data): array
    {
        foreach ($data as $key => $value) {
            $value['key'] = $value['id'];
            unset($value['id']);
            $data[$key] = $value;
        }
        return $data;
    }
}