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
    public static $activeData = [
        [
            'mock_id' => 1,
            'mock_value' => 'aaa',
        ],
        [
            'mock_id' => 2,
            'mock_value' => 'bbb',
        ]
    ];

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
        return static::$activeData;
    }
}
