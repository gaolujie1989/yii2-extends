<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\tests\unit\mocks;


use lujie\ar\relation\behaviors\tests\unit\fixtures\models\TestOrder;
use lujie\sales\channel\BaseSalesChannelConnector;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\db\BaseActiveRecord;

/**
 * Class MockSalesChannelConnector
 * @package lujie\sales\channel\tests\unit\mocks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class MockSalesChannelConnector extends BaseSalesChannelConnector
{
    /**
     * @param SalesChannelOrder $salesChannelOrder
     * @param array $externalOrder
     * @return BaseActiveRecord
     * @inheritdoc
     */
    protected function createOutboundOrder(SalesChannelOrder $salesChannelOrder, array $externalOrder): BaseActiveRecord
    {
        return new TestOrder([
            'status' => 0,
            'updated_at' => time(),
        ]);
    }
}