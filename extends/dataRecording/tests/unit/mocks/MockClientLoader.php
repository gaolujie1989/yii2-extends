<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\data\recording\tests\unit\mocks;

use lujie\data\loader\BaseDataLoader;

/**
 * Class MockApiClient
 * @package lujie\data\recording\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockClientLoader extends BaseDataLoader
{
    public function get($key)
    {
        return ['class' => MockApiClient::class];
    }
}
