<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use yii\base\BaseObject;
use yii\helpers\ArrayHelper;

/**
 * Class BaseImporter
 * @package lujie\data\exchange\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
abstract class BaseDbPipeline extends BaseObject implements DbPipelineInterface
{
    /**
     * @var array
     */
    public $indexKeys;

    /**
     * @var array
     */
    protected $affectedRowCounts = [
        self::AFFECTED_CREATED => 0,
        self::AFFECTED_UPDATED => 0,
        self::AFFECTED_SKIPPED => 0
    ];

    /**
     * @return array
     * @inheritdoc
     */
    public function getAffectedRowCounts(): array
    {
        return $this->affectedRowCounts;
    }

    /**
     * @param $data
     * @return array
     * @inheritdoc
     */
    protected function indexData($data): array
    {
        if ($this->indexKeys) {
            return ArrayHelper::index($data, function($values) {
                $condition = array_intersect_key($values, array_flip($this->indexKeys));
                return md5(json_encode($condition));
            });
        }
        return $data;
    }
}
