<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\extend\tests\unit\mocks;


use yii\db\ActiveQuery;

/**
 * Class MockActiveQuery
 * @package lujie\extend\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockActiveQuery extends ActiveQuery
{
    /**
     * @param string $q
     * @param null $db
     * @return int|string
     * @inheritdoc
     */
    public function count($q = '*', $db = null)
    {
        return 2;
    }

    /**
     * @param null $db
     * @return array|\yii\db\ActiveRecord[]
     * @inheritdoc
     */
    public function all($db = null): array
    {
        return [
            [
                'id' => 1,
                'xxx' => 'aaa'
            ],
            [
                'id' => 2,
                'xxx' => 'bbb'
            ]
        ];
    }
}
