<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\mocks;


use lujie\data\loader\BaseDataLoader;

class MockFulfillmentServiceLoader extends BaseDataLoader
{
    public function get($key)
    {
        return new MockFulfillmentService();
    }
}
