<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use yii\base\Arrayable;
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
     * @var int
     */
    public $chunkSize = 50;

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
                return $this->getIndexValue($values);
            });
        }
        return $data;
    }

    /**
     * @param $values
     * @return string
     * @inheritdoc
     */
    protected function getIndexValue($values): string
    {
        if (is_object($values) && $values instanceof Arrayable) {
            $condition = $values->toArray($this->indexKeys);
            return json_encode($condition);
        }
        if (is_object($values)) {
            $values = ArrayHelper::toArray($values);
        }
        $condition = array_intersect_key($values, array_flip($this->indexKeys));
        return json_encode($condition);
    }
}
