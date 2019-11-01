<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use lujie\extend\db\IdFieldTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use yii\db\BaseActiveRecord;

/**
 * Class MockActiveQuery
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockActiveRecord extends BaseActiveRecord
{
    use TraceableBehaviorTrait, IdFieldTrait, SaveTrait, TransactionTrait;

    public static $inserts = [];

    public static $updates = [];

    public static function primaryKey(): array
    {
        return ['mock_id'];
    }

    public static function find(): MockActiveQuery
    {
        return new MockActiveQuery(static::class);
    }

    public function insert($runValidation = true, $attributes = null)
    {
        static::$inserts[] = [$runValidation, $attributes, $this->attributes];
        return 1;
    }

    public static function updateAll($attributes, $condition = '')
    {
        static::$updates[] = [$attributes, $condition];
        return 1;
    }

    public static function getDb()
    {
        return null;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return ['mock_id', 'mock_value', 'updated_by', 'updated_at'];
    }

    /**
     * @param $model
     * @inheritdoc
     */
    public function prepareArray($model): array
    {
        $model['prepared'] = 1;
        return $model;
    }

    /**
     * @param $operation
     * @return bool
     * @inheritdoc
     */
    public function isTransactional($operation)
    {
        $scenario = $this->getScenario();
        $transactions = $this->transactions();

        return isset($transactions[$scenario]) && ($transactions[$scenario] & $operation);
    }
}
