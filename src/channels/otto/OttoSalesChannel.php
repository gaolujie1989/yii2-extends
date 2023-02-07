<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\otto;

use lujie\otto\OttoRestClient;
use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;

/**
 * Class OttoSalesChannel
 * @package lujie\sales\channel\channels\otto
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class OttoSalesChannel extends BaseSalesChannel
{
    /**
     * @var OttoRestClient
     */
    public $client;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client, OttoRestClient::class);
    }

    #region Order Action ship/cancel

    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        // TODO: Implement shipSalesOrder() method.
    }

    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        // TODO: Implement cancelSalesOrder() method.
    }

    #endregion

    #region Order Pull

    protected function getExternalOrders(array $externalOrderKeys): array
    {
        // TODO: Implement getExternalOrders() method.
    }

    protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array
    {
        // TODO: Implement getNewExternalOrders() method.
    }

    #endregion

    #region Item Push

    /**
     * @param BaseActiveRecord $item
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @throws NotSupportedException
     * @inheritdoc
     */
    protected function formatExternalItemData(BaseActiveRecord $item, SalesChannelItem $salesChannelItem): ?array
    {
        throw new NotSupportedException('NotSupported');
    }

    protected function getExternalItem(array $externalItem): ?array
    {

    }

    protected function saveExternalItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
    }

    #endregion

}