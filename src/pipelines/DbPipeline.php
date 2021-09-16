<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use lujie\extend\helpers\ActiveDataHelper;
use lujie\extend\helpers\IdentityHelper;
use lujie\extend\helpers\ValueHelper;
use yii\db\ActiveRecord;
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
     * @var ?ActiveRecord
     */
    public $modelClass;

    /**
     * @var string
     */
    public $createdAtField = 'created_at';
    /**
     * @var string
     */
    public $updatedAtField = 'updated_at';
    /**
     * @var string
     */
    public $createdByField = 'created_by';
    /**
     * @var string
     */
    public $updatedByField = 'updated_by';

    /**
     * @var bool
     */
    public $updateByPrimaryKey = true;

    /**
     * @var bool
     */
    public $skipIfEqual = true;

    /**
     * @var bool
     */
    public $skipEqualStrict = true;

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if ($this->modelClass) {
            $this->db = $this->modelClass::getDb();
            $this->table = $this->modelClass::tableName();
        }
        $this->db = Instance::ensure($this->db, Connection::class);
    }

    /**
     * @param array $data
     * @return bool
     * @throws \Throwable
     * @inheritdoc
     */
    protected function processInternal(array $data): bool
    {
        $columns = $this->db->getTableSchema($this->table)->columns;
        array_walk($data, static function (&$values) use ($columns) {
            $values = array_intersect_key($values, $columns);
        });

        if ($this->indexKeys) {
            [$insertRows, $updateRows] = $this->createRows($data);
        } else {
            $insertRows = array_values($data);
            $updateRows = [];
        }
        if (!$this->insert) {
            $insertRows = [];
        }
        if (!$this->update) {
            $updateRows = [];
        }

        $this->appendTraceableToRows($insertRows, $updateRows);

        $callable = function () use ($insertRows, $updateRows) {
            $affectedRowCounts = $this->affectedRowCounts;
            foreach ($insertRows as $values) {
                $n = $this->db->createCommand()->insert($this->table, $values)->execute();
                if ($n) {
                    $affectedRowCounts[self::AFFECTED_CREATED] += $n;
                } else {
                    $affectedRowCounts[self::AFFECTED_SKIPPED]++;
                }
            }
            foreach ($updateRows as [$values, $condition]) {
                $n = $this->db->createCommand()->update($this->table, $values, $condition)->execute();
                if ($n) {
                    $affectedRowCounts[self::AFFECTED_UPDATED] += $n;
                } else {
                    $affectedRowCounts[self::AFFECTED_SKIPPED]++;
                }
            }
            $this->affectedRowCounts = $affectedRowCounts;
            return true;
        };
        return $this->db->transaction($callable);
    }

    /**
     * @param array $data
     * @return array[] return [$insertRows, $updateRows]
     * @throws \yii\base\NotSupportedException
     * @inheritdoc
     */
    protected function createRows(array $data): array
    {
        $insertRows = [];
        $updateRows = [];
        $dataChunks = array_chunk($data, $this->chunkSize, true);
        $primaryKeys = $this->updateByPrimaryKey ? $this->db->getTableSchema($this->table)->primaryKey : [];
        $selectColumns = array_unique(array_merge($this->indexKeys, $primaryKeys));
        foreach ($dataChunks as $chunkedData) {
            $existRows = [];
            if ($this->indexKeys) {
                $conditions = ArrayHelper::getColumn($chunkedData, function ($values) {
                    return array_intersect_key($values, array_flip($this->indexKeys));
                }, false);
                array_unshift($conditions, 'OR');
                $indexQuery = (new Query())->from($this->table)
                    ->andWhere($conditions)
                    ->indexBy(function ($values) {
                        return $this->getIndexValue($values);
                    });

                if (!$this->skipIfEqual) {
                    $indexQuery->select($selectColumns);
                }
                $existRows = $indexQuery->all($this->db);
                if ($this->skipIfEqual) {
                    $existRows = ActiveDataHelper::phpTypecast($existRows, $this->table, $this->db, true);
                }
            }
            foreach ($chunkedData as $indexValue => $values) {
                if ($this->indexKeys && isset($existRows[$indexValue])) {
                    if ($this->skipIfEqual) {
                        $existValues = array_intersect_key($existRows[$indexValue], $values);
                        if (ValueHelper::isArrayEqual($existValues, $values, $this->skipEqualStrict)) {
                            $this->affectedRowCounts[self::AFFECTED_SKIPPED]++;
                            continue;
                        }
                    }
                    if ($primaryKeys) {
                        $condition = array_intersect_key($existRows[$indexValue], array_flip($primaryKeys));
                    } else {
                        $condition = array_intersect_key($existRows[$indexValue], array_flip($this->indexKeys));
                    }
                    $updateRows[] = [$values, $condition];
                } else {
                    $insertRows[] = $values;
                }
            }
        }
        return [$insertRows, $updateRows];
    }

    /**
     * @param array $insertRows
     * @param array $updateRows
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    protected function appendTraceableToRows(array &$insertRows, array &$updateRows): void
    {
        $now = time();
        $userId = IdentityHelper::getId();
        $columns = $this->db->getTableSchema($this->table)->columns;
        $createdTraceable = [];
        if (isset($columns[$this->createdAtField])) {
            $createdTraceable[$this->createdAtField] = $now;
        }
        if (isset($columns[$this->createdByField])) {
            $createdTraceable[$this->createdByField] = $userId;
        }
        $updatedTraceable =[];
        if (isset($columns[$this->updatedAtField])) {
            $updatedTraceable[$this->updatedAtField] = $now;
        }
        if (isset($columns[$this->updatedByField])) {
            $updatedTraceable[$this->updatedByField] = $userId;
        }

        array_walk($insertRows, static function (&$row) use ($createdTraceable, $updatedTraceable) {
            $row = array_merge($createdTraceable, $updatedTraceable, $row);
        });
        array_walk($updateRows, static function (&$row) use ($updatedTraceable) {
            $row[0] = array_merge($updatedTraceable, $row[0]);
        });
    }
}
