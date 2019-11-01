<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;


use yii\db\ActiveRecord;

/**
 * Class MockActiveQuery
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockActiveRecord extends ActiveRecord
{
    /**
     * @return array
     * @inheritdoc
     */
    public function attributes(): array
    {
        return ['id', 'xxx'];
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
