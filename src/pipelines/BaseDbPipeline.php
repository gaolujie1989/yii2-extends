<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use Yii;
use yii\base\BaseObject;
use yii\db\IntegrityException;
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
     * @var bool
     */
    public $filterNull = true;

    /**
     * @var bool
     */
    public $insert = true;

    /**
     * @var bool
     */
    public $update = true;

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
     * @return bool
     * @throws IntegrityException
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        if ($this->filterNull) {
            $data = array_map(static function ($values) {
                return array_filter($values, static function ($value) {
                    return $value !== null;
                });
            }, $data);
        }

        if ($this->indexKeys) {
            $data = $this->indexData($data);
        }

        try {
            return $this->processInternal($data);
        } catch (IntegrityException $exception) {
            Yii::warning($exception->getMessage(), __METHOD__);
            return $this->processInternal($data);
        }
    }

    /**
     * @param array $data
     * @return bool
     * @throws IntegrityException
     * @inheritdoc
     */
    abstract protected function processInternal(array $data): bool;

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
     * @param $values
     * @return string
     * @throws \Exception
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
