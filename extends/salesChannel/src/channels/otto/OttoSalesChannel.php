<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\sales\channel\channels\otto;

use lujie\data\exchange\transformers\TransformerInterface;
use lujie\otto\OttoRestClient;
use lujie\sales\channel\BaseSalesChannel;
use lujie\sales\channel\constants\SalesChannelConst;
use lujie\sales\channel\models\SalesChannelItem;
use lujie\sales\channel\models\SalesChannelOrder;
use yii\base\InvalidConfigException;
use yii\base\UserException;
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
     * @var string
     */
    public $externalItemKeyField = 'sku';

    /**
     * @var string
     */
    public $externalOrderKeyField = 'salesOrderId';

    /**
     * @var TransformerInterface
     */
    public $orderTransformer = OttoSalesChannelOrderTransformer::class;

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client, OttoRestClient::class);
    }

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

    #region Order Push

    protected function getExternalOrder(string $externalOrderKey): ?array
    {
        return $this->client->getV4Order(['salesOrderId' => $externalOrderKey]);
    }

    protected function saveExternalOrder(array $externalOrder, SalesChannelOrder $salesChannelOrder): ?array
    {
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_CANCELLED) {
            $this->client->cancelV4Order(['salesOrderId' => $salesChannelOrder->external_order_key]);
            return $this->getExternalOrder($salesChannelOrder->external_order_key);
        }
        if ($salesChannelOrder->sales_channel_status === SalesChannelConst::CHANNEL_STATUS_TO_SHIPPED) {
            $this->client->createV1Shipment(array_merge($externalOrder, [
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
            ]));
            return $this->getExternalOrder($salesChannelOrder->external_order_key);
        }
        return null;
    }

    #endregion

    #region Item Push

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
        if (preg_match('/\w{8}(-\w{4}){3}-\w{12}/', $links['self'], $matches)) {
            $pushedResult['processUuid'] = $matches[0];
            $salesChannelItem->external_item_no = $externalItem['sku'];
            $salesChannelItem->item_pushed_result = $pushedResult;
            $salesChannelItem->item_pushed_updated_after_at = strtotime($responseData['pingAfter']);
            $salesChannelItem->save(false);
        } else {
            throw new UserException('Invalid links: ' . Json::encode($links));
        }
        return $externalItem;
    }

    /**
     * @param SalesChannelItem $salesChannelItem
     * @return bool
     * @throws UserException
     * @inheritdoc
     */
    public function checkSalesItemUpdated(SalesChannelItem $salesChannelItem): bool
    {
        $pushedResult = $salesChannelItem->item_pushed_result;
        $processUuid = $pushedResult['processUuid'];
        if (empty($processUuid)) {
            return false;
        }
        $taskResponseData = $this->client->getV3ProductUpdateTask(['processUuid' => $processUuid]);
        if ($taskResponseData['state'] !== 'done') {
            $salesChannelItem->item_pushed_updated_after_at = strtotime($taskResponseData['pingAfter']);
            return $salesChannelItem->save(false);
        }
        $statusList = ['succeeded', 'failed', 'unchanged'];
        foreach ($statusList as $status) {
            if ($taskResponseData[$status]) {
                $statusResponseData = $this->client->getV3ProductUpdateTaskStatus(['processUuid' => $processUuid, 'status' => $status]);
                $statusResults = ArrayHelper::index($statusResponseData['results'], 'variation');
                $resultKey = '/v3/products/' . $salesChannelItem->external_item_no;
                $variationStatusResult = $statusResults[$resultKey] ?? null;
                if ($variationStatusResult) {
                    $salesChannelItem->item_pushed_updated_after_at = 0;
                    if ($status === 'succeeded') {
                        return $salesChannelItem->save(false);
                    }
                    $errors = $variationStatusResult['errors'];
                    throw new UserException(Json::encode($errors));
                }
            }
        }
        return false;
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
