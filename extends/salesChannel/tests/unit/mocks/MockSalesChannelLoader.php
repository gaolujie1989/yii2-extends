<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tests\unit\mocks;

use lujie\data\loader\BaseDataLoader;
use lujie\sales\channel\models\SalesChannelAccount;

/**
 * Class MockSalesChannelLoader
 * @package lujie\sales\channel\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockSalesChannelLoader extends BaseDataLoader
{
    public $accountId = 1;

    public function get($key)
    {
        return new MockSalesChannel([
            'account' => new SalesChannelAccount([
                'account_id' => $this->accountId
            ]),
        ]);
    }
}
