<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\exchange\pipelines;

use yii\db\Connection;
use yii\db\Query;
use yii\di\Instance;

/**
 * Class ActiveRecordImporter
 * @package lujie\data\exchange\pipelines
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DbPipeline extends BasePipeline
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
        $this->errors = [];
        $this->affectedRowCounts = [];

        $insertRows = [];
        $updateRows = [];
        $columns = $this->db->getTableSchema($this->table)->columns;
        $data = array_map(static function($values) use ($columns) {
            return array_intersect_key($values, $columns);
        }, $data);

        if ($this->indexKeys) {
            foreach ($data as $key => $values) {
                $condition = array_intersect_key($values, array_flip($this->indexKeys));
                $exists = (new Query())->from($this->table)->andWhere($condition)->exists($this->db);
                if ($exists) {
                    $updateRows[] = [$values, $condition];
                } else {
                    $insertRows[] = $values;
                }
            }
        } else {
            $insertRows[] = array_values($data);
        }

        $callable = function () use ($insertRows, $updateRows) {
            $counts = [
                self::AFFECTED_CREATED => 0,
                self::AFFECTED_UPDATED => 0,
                self::AFFECTED_SKIPPED => 0
            ];
            foreach ($insertRows as $values) {
                $n = $this->db->createCommand()->insert($this->table, $values)->execute();
                if ($n) {
                    $counts[self::AFFECTED_CREATED] += $n;
                } else {
                    $counts[self::AFFECTED_SKIPPED]++;
                }
            }
            foreach ($updateRows as [$values, $condition]) {
                $n = $this->db->createCommand()->update($this->table, $values, $condition)->execute();
                if ($n) {
                    $counts[self::AFFECTED_UPDATED] += $n;
                } else {
                    $counts[self::AFFECTED_SKIPPED]++;
                }
            }
            $this->affectedRowCounts = $counts;
            return true;
        };
        return $this->db->transaction($callable);
    }
}
