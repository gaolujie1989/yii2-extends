<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\otto;

use lujie\extend\constants\ExecStatusConst;
use lujie\extend\helpers\ExecuteException;
use lujie\otto\OttoRestClient;
use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\InvalidConfigException;
use yii\base\NotSupportedException;
use yii\base\UserException;
use yii\db\BaseActiveRecord;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

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
        return true;
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

    /**
     * @param array $externalItem
     * @param SalesChannelItem $salesChannelItem
     * @return array|null
     * @throws UserException
     * @inheritdoc
     */
    protected function saveExternalItem(array $externalItem, SalesChannelItem $salesChannelItem): ?array
    {
        $pushedResult = $salesChannelItem->item_pushed_result ?: [];
        unset($pushedResult['processUuid']);

        $responseData = $this->client->saveV3Product([$externalItem]);
        $links = ArrayHelper::map($responseData['links'], 'rel', 'href');
        if (preg_match('/w{8}(-\w{4}){3}-\w{12}/', $links['self'], $matches)) {
            $pushedResult['processUuid'] = $matches[0];
            $pushedResult['pingAfter'] = $responseData['pingAfter'];
            $salesChannelItem->item_pushed_result = $pushedResult;
            $salesChannelItem->save(false);
        } else {
            throw new UserException('Invalid links: ' . Json::encode($links));
        }

        return $externalItem;
    }

    /**
     * @param SalesChannelItem $salesChannelItem
     * @return array
     * @inheritdoc
     */
    public function checkExternalItemUpdateStatus(SalesChannelItem $salesChannelItem): array
    {
        $pushedResult = $salesChannelItem->item_pushed_result ?: [];
        if ($salesChannelItem->item_pushed_status === ExecStatusConst::EXEC_STATUS_REMOTE_PROCESSING
            && isset($pushedResult['processUuid'])) {
            if (empty($pushedResult['pingAfter']) || strtotime($pushedResult['pingAfter']) < time()) {
                $this->client->getV3ProductUpdateTask(['processUuid' => $pushedResult]);
            }
        }
    }

    #endregion

    #region Item Stock Push

    /**
     * @param array $externalItemStocks
     * @return array|null
     * @inheritdoc
     */
    protected function saveExternalItemStocks(array $externalItemStocks): ?array
    {
        return $this->client->saveV2Quantity($externalItemStocks);
    }

    #endregion

}