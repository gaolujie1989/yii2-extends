<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\otto;

use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\db\BaseActiveRecord;

/**
 * Class OttoSalesChannel
 * @package lujie\sales\channel\channels\otto
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoSalesChannel extends BaseSalesChannel
{
    protected function getExternalOrders(array $externalOrderKeys): array
    {
        // TODO: Implement getExternalOrders() method.
    }

    protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array
    {
        // TODO: Implement getNewExternalOrders() method.
    }

    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        // TODO: Implement shipSalesOrder() method.
    }

    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        // TODO: Implement cancelSalesOrder() method.
    }

    protected function formatExternalItemData(BaseActiveRecord $item, SalesChannelItem $salesChannelItem): ?array
    {
        // TODO: Implement formatExternalItemData() method.
    }

    protected function getExternalItem(array $externalItem): ?array
    {
        // TODO: Implement getExternalItem() method.
    }

    protected function saveExternalItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        // TODO: Implement saveExternalItem() method.
    }
}