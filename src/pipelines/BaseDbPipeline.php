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
     * @param array $data
     * @return array
     * @inheritdoc
     */
    protected function indexData(array $data): array
    {
        if ($this->indexKeys) {
            return ArrayHelper::index($data, function ($values) {
                return $this->getIndexValue($values);
            });
        }
        return $data;
    }

    /**
     * @param array|object $values
     * @return string
     * @inheritdoc
     */
    protected function getIndexValue($values): string
    {
        $indexValues = [];
        foreach ($this->indexKeys as $indexKey) {
            $indexValues[] = ArrayHelper::getValue($values, $indexKey, 'NULL');
        }
        return implode('-', $indexValues);
    }
}
