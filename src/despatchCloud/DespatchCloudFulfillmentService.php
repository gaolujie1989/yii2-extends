<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\despatchCloud;

use lujie\extend\authclient\JsonRpcException;
use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use yii\di\Instance;

/**
 * Class PmFulfillmentService
 * @package lujie\fulfillment\pm
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DespatchCloudFulfillmentService extends BaseFulfillmentService
{
    /**
     * @var DespatchCloudRestClient
     */
    public $client;

    #region External Model Key Field

    #endregion

    #region DespatchCloud custom push field

    /**
     * @var array
     */
    public $defaultItemData = [
        'type' => 0,
        'dropshipping_type' => 0,
        'product_box_type' => 1,
        'product_box_quantity' => 1,
        'country_of_origin' => 'china',
    ];

    #endregion

    /**
     * @throws \yii\base\InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        $this->client = Instance::ensure($this->client, DespatchCloudRestClient::class);
    }

    #region Item Push

    /**
     * @param Item $item
     * @param FulfillmentItem $fulfillmentItem
     * @return array
     * @inheritdoc
     */
    protected function formatExternalItemData(Item $item, FulfillmentItem $fulfillmentItem): array
    {
        $ean = $item->getBarcode('EAN');
        $own = $item->getBarcode('OWN');
        return array_merge($this->defaultItemData, [
            'name' => $item->itemName,
            'sku' => $item->itemNo,
            'item_barcode' => $ean ?: $own,
            'item_barcode_2' => $ean ? $own : '',
            'product_weight' => $item->weightG / 1000,
            'product_length' => $item->lengthMM / 10,
            'product_width' => $item->widthMM / 10,
            'product_height' => $item->heightMM / 10,
            'image' => $item->imageUrls ? reset($item->imageUrls) : '',
        ]);
    }

    /**
     * @param Item $item
     * @return array|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function getExternalItem(Item $item): ?array
    {
        $inventories = $this->client->listInventories(['sku' => $item->itemNo]);
        return $inventories['data'][0] ?? null;
    }

    /**
     * @param array $externalItem
     * @param FulfillmentItem $fulfillmentItem
     * @return array|null
     * @throws JsonRpcException
     * @inheritdoc
     */
    protected function saveExternalItem(array $externalItem, FulfillmentItem $fulfillmentItem): ?array
    {
        if ($fulfillmentItem->external_item_key) {
            return $this->client->updateInventory($externalItem);
        }
        return $this->client->createInventory($externalItem);
    }

    /**
     * @param FulfillmentItem $fulfillmentItem
     * @param array $externalItem
     * @return bool
     * @inheritdoc
     */
    protected function updateFulfillmentItem(FulfillmentItem $fulfillmentItem, array $externalItem): bool
    {
        if (empty($fulfillmentItem->external_item_additional)) {
            $fulfillmentItem->external_item_additional = ['sku' => $externalItem['sku']];
        }
        if (empty($fulfillmentItem->external_created_at)) {
            $fulfillmentItem->external_created_at = strtotime($externalItem['created_at']);
        }
        $fulfillmentItem->external_updated_at = strtotime($externalItem['updated_at']);
        return parent::updateFulfillmentItem($fulfillmentItem, $externalItem);
    }

    #endregion

    #region Order Push

    protected function formatExternalOrderData(Order $order, FulfillmentOrder $fulfillmentOrder): array
    {

    }

    #endregion
}
