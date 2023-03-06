<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\mocks;

use lujie\data\loader\ArrayDataLoader;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;

/**
 * Class MockArrayDataLoader
 * @package lujie\fulfillment\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockFulfillmentDataLoader extends ArrayDataLoader
{
    public function get($key)
    {
        if ($key instanceof FulfillmentItem) {
            return parent::get($key->item_id);
        }
        if ($key instanceof FulfillmentOrder) {
            return parent::get($key->order_id);
        }
        return parent::get($key);
    }
}
