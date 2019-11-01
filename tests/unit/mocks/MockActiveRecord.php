<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;

use yii\db\BaseActiveRecord;

/**
 * Class MockActiveQuery
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockActiveRecord extends BaseActiveRecord
{
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
        return 0;
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
        return ['mock_id', 'mock_key', 'updated_by', 'updated_at'];
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


}
