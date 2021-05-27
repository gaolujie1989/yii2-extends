<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use lujie\extend\db\AliasFieldTrait;
use lujie\extend\db\DbConnectionTrait;
use lujie\extend\db\DeleteTrait;
use lujie\extend\db\SaveTrait;
use lujie\extend\db\TraceableBehaviorTrait;
use lujie\extend\db\TransactionTrait;
use yii\db\ActiveRecord;

/**
 * Class MockActiveQuery
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockActiveRecord extends ActiveRecord
{
    use TraceableBehaviorTrait, AliasFieldTrait, SaveTrait, DeleteTrait, TransactionTrait, DbConnectionTrait;

    public static $inserts = [];

    public static $updates = [];

    public static $columns = ['mock_id', 'mock_value', 'created_by', 'created_at', 'updated_by', 'updated_at'];

    public static $rules = [];

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

    public static function updateAll($attributes, $condition = '', $params = [])
    {
        static::$updates[] = [$attributes, $condition];
        return 1;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return static::$columns;
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function rules(): array
    {
        return static::$rules;
    }

    /**
     * @param $row
     * @inheritdoc
     */
    public static function prepareArray(array $row): array
    {
        $row['prepared'] = 1;
        return $row;
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

    public function getMockCopy()
    {
        return $this->hasOne(MockActiveRecord::class, ['mock_id' => 'mock_id']);
    }

    /**
     * @return array
     * @inheritdoc
     */
    public function extraFields()
    {
        return array_merge(parent::extraFields(), [
            'mockCopy' => 'mockCopy'
        ]);
    }
}
