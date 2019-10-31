<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;
use yii\helpers\ArrayHelper;

/**
 * Class ActiveRecordImporter
 * @package lujie\data\exchange\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbPipeline extends BaseDbPipeline
{
    /**
     * @var Connection
     */
    public $db = 'db';

    /**
     * @var string
     */
    public $table;

    /**
     * @var bool
     */
    public $filterNull = true;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->db = Instance::ensure($this->db, Connection::class);
    }

    /**
     * @param $data
     * @return bool|mixed
     * @throws \Throwable
     * @inheritdoc
     */
    public function process(array $data): bool
    {
        if ($this->filterNull) {
            $data = array_map('array_filter', $data);
        }
        
        $columns = $this->db->getTableSchema($this->table)->columns;
        $data = array_map(static function ($values) use ($columns) {
            return array_intersect_key($values, $columns);
        }, $data);

        if ($this->indexKeys) {
            $data = $this->indexData($data);
            [$insertRows, $updateRows] = $this->createRows($data);
        } else {
            $insertRows = array_values($data);
            $updateRows = [];
        }

        $callable = function () use ($insertRows, $updateRows) {
            foreach ($insertRows as $values) {
                $n = $this->db->createCommand()->insert($this->table, $values)->execute();
                if ($n) {
                    $this->affectedRowCounts[self::AFFECTED_CREATED] += $n;
                } else {
                    $this->affectedRowCounts[self::AFFECTED_SKIPPED]++;
                }
            }
            foreach ($updateRows as [$values, $condition]) {
                $n = $this->db->createCommand()->update($this->table, $values, $condition)->execute();
                if ($n) {
                    $this->affectedRowCounts[self::AFFECTED_UPDATED] += $n;
                } else {
                    $this->affectedRowCounts[self::AFFECTED_SKIPPED]++;
                }
            }
            return true;
        };
        return $this->db->transaction($callable);
    }

    /**
     * @param array $data
     * @return array return [$insertRows, $updateRows]
     * @inheritdoc
     */
    protected function createRows(array $data): array
    {
        $insertRows = [];
        $updateRows = [];
        $dataChunks = array_chunk($data, $this->chunkSize, true);
        foreach ($dataChunks as $chunkedData) {
            $existRows = [];
            if ($this->indexKeys) {
                $conditions = ArrayHelper::getColumn($chunkedData, function ($values) {
                    return array_intersect_key($values, array_flip($this->indexKeys));
                }, false);
                array_unshift($conditions, 'OR');
                $existRows = (new Query())->from($this->table)
                    ->andWhere($conditions)
                    ->indexBy(function ($values) {
                        return $this->getIndexValue($values);
                    })->all($this->db);
            }
            foreach ($chunkedData as $indexValue => $values) {
                if ($this->indexKeys && isset($existRows[$indexValue])) {
                    $condition = array_intersect_key($values, array_flip($this->indexKeys));
                    $updateRows[] = [$values, $condition];
                } else {
                    $insertRows[] = $values;
                }
            }
        }
        return [$insertRows, $updateRows];
    }
}
