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

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    public function shipSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        $additional = $channelOrder->additional;
        $this->client->createV1Shipment([
            'trackingKey' => [
                'carrier' => $additional['carrier'],
                'trackingNumber' => $additional['trackingNumbers'],
            ],
            'shipDate' => date('c', $additional['shipped_at']),
            'shipFromAddress' => [
                'city' => '',
                'countryCode' => '',
                'zipCode' => '',
            ],
            'positionItems' => [
                [
                    'positionItemId' => '',
                    'salesOrderId' => '',
                    'returnTrackingKey' => '',
                ]
            ]
        ]);
    }

    /**
     * @param SalesChannelOrder $channelOrder
     * @return bool
     * @inheritdoc
     */
    public function cancelSalesOrder(SalesChannelOrder $channelOrder): bool
    {
        $this->client->cancelV4Order(['salesOrderId' => $channelOrder->external_order_key]);
        return true;
    }

    #endregion

    #region Order Pull

    /**
     * @param array $externalOrderKeys
     * @return array
     * @inheritdoc
     */
    protected function getExternalOrders(array $externalOrderKeys): array
    {
        $externalOrders = [];
        foreach ($externalOrderKeys as $externalOrderKey) {
            $externalOrders[$externalOrderKey] = $this->client->getV4Order($externalOrderKey);
        }
        return $externalOrders;
    }

    /**
     * @param int $createdAtFrom
     * @param int $createdAtTo
     * @return array
     * @inheritdoc
     */
    protected function getNewExternalOrders(int $createdAtFrom, int $createdAtTo): array
    {
        $orders = $this->client->eachV4Orders([
            'fromOrderDate' => date('c', $createdAtFrom),
            'toOrderDate' => date('c', $createdAtTo),
        ]);
        return iterator_to_array($orders, false);
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

    /**
     * @param array $externalItem
     * @return array|null
     * @inheritdoc
     */
    protected function getExternalItem(array $externalItem): ?array
    {
        return null;
    }

    protected function saveExternalItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {

    }

    #endregion

}