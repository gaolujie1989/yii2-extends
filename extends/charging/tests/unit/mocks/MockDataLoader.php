<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\charging\tests\unit\mocks;

use lujie\data\loader\ArrayDataLoader;
use yii\db\BaseActiveRecord;

/**
 * Class MockDataLoader
 * @package lujie\charging\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockDataLoader extends ArrayDataLoader
{
    /**
     * @param BaseActiveRecord $key
     * @return array|mixed|null
     * @inheritdoc
     */
    public function get($key)
    {
        $key = $key->getPrimaryKey();
        return parent::get($key);
    }
}
