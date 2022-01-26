<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\plentyMarkets\rest;

use lujie\extend\caching\CachingTrait;
use lujie\extend\helpers\CsvHelper;
use lujie\plentyMarkets\PlentyMarketsAdminClient;
use lujie\plentyMarkets\PlentyMarketsConst;
use lujie\plentyMarkets\PlentyMarketsRestClient;
use phpDocumentor\Reflection\Types\Boolean;
use Yii;
use yii\authclient\InvalidResponseException;
use yii\base\InvalidConfigException;
use yii\console\Controller;
use yii\di\Instance;
use yii\helpers\ArrayHelper;
use yii\helpers\Console;
use yii\helpers\FileHelper;
use yii\helpers\VarDumper;

/**
 * Class PmController
 * @package console\controllers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class PmController extends Controller
{
    use CachingTrait;

    /**
     * @var PlentyMarketsRestClient
     */
    public $restClient = [];

    /**
     * @var PlentyMarketsAdminClient
     */
    public $adminClient = [];

    /**
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function init(): void
    {
        parent::init();
        if (isset(Yii::$app->params['pm.url'])) {
            $this->restClient = [
                'apiBaseUrl' => Yii::$app->params['pm.url'],
                'username' => Yii::$app->params['pm.username'],
                'password' => Yii::$app->params['pm.password'],
            ];
        }
        if (isset(Yii::$app->params['pm.plentyId'])) {
            $this->adminClient = [
                'plentyId' => Yii::$app->params['pm.plentyId'],
                'username' => Yii::$app->params['pm.username'],
                'password' => Yii::$app->params['pm.password'],
                'requestOptions' => [
//                CURLOPT_PROXY => 'https://hk1.out.skylinktools.com',
                    CURLOPT_CONNECTTIMEOUT => 60,
                    CURLOPT_TIMEOUT => 180,
                ],
            ];
        }
        if ($this->restClient) {
            $this->restClient = Instance::ensure($this->restClient, PlentyMarketsRestClient::class);
        }
        if ($this->adminClient) {
            $this->adminClient = Instance::ensure($this->adminClient, PlentyMarketsAdminClient::class);
        }
    }

    #region PM Order Update

    public function actionUpdateOrderTimestamps($orderIds): void
    {
        $orderIds = array_filter(explode(',', $orderIds));
        Console::startProgress($done = 0, $total = count($orderIds));
        foreach ($orderIds as $orderId) {
            Console::updateProgress($done, $total, $orderId);
            $this->restClient->updateOrder(['id' => $orderId, 'ownerId' => 17]);
            $order = $this->restClient->getOrder(['id' => $orderId]);
            VarDumper::dump(ArrayHelper::filter($order, ['id', 'ownerId', 'updatedAt']));
            Console::updateProgress(++$done, $total, $orderId);
        }
        Console::endProgress();
    }

    public function actionUpdateOrderAddresses(): void
    {
        $orderAddresses = [
            'xxx' => [
                'companyName' => '',
                'firstName' => 'xxx',
                'lastName' => 'xxx',
                'street' => 'xxx',
                'houseNo' => 'xxx',
                'additional' => '',
                'zipCode' => 'xxx',
                'city' => 'xxx',
                'country' => 'xxx',
                'phone' => 'xxx',
            ],
        ];
        Console::startProgress($done = 0, $total = count($orderAddresses));
        foreach ($orderAddresses as $orderId => $address) {
            $this->restClient->updateOrderAddresses($orderId, $address);
//            $this->restClient->updateOrder(['id' => $orderId, 'statusId' => 5]);
            Console::updateProgress(++$done, $total);
        }
        Console::endProgress();
    }

    /**
     * not working
     */
    public function actionUpdateOrderItems(): void
    {
        $order = [
            'id' => 2121620,
            'orderItems' => [
                [
                    'typeId' => 1,
                    'referrerId' => 4.05,
                    'quantity' => 1,
                    'itemVariationId' => 8401,
                    'countryVatId' => 1,
                    'vatField' => 0,
                    'vatRate' => 22,
                    'orderItemName' => "CCLIFE 2in1 Set manubri Regolabili Pesi Fitness 2 manubri Regolabili con Barra Bilanciere Fitness Palestra Pesi 20 30 4OKG-ZERRO",
                    'shippingProfileId' => 1,
                    'amounts' => [
                        [
                            'isSystemCurrency' => true,
                            'currency' => "EUR",
                            'exchangeRate' => 1,
                            'priceOriginalGross' => 60.91,
                            'surcharge' => 0,
                            'discount' => 0,
                            'isPercentage' => true
                        ]
                    ],
                    'properties' => [
                        [
                            'typeId' => PlentyMarketsConst::ORDER_ITEM_PROPERTY_TYPE_IDS['WAREHOUSE'],
                            'value' => 108,
                        ]
                    ]
                ]
            ]
        ];
        $this->restClient->updateOrder($order);
    }

    public function actionUpdateOrderWarehouses($orderIds, $warehouseId = 108): void
    {
        $orderIds = array_filter(explode(',', $orderIds));
        $orderIds = array_unique($orderIds);
        Console::startProgress($done = 0, $total = count($orderIds));
        foreach ($orderIds as $orderId) {
            Console::updateProgress($done, $total, $orderId . ' Requesting...');
            $this->restClient->updateOrderWarehouse($orderId, $warehouseId, false);
            //for update order timestamp
            Console::updateProgress(++$done, $total, $orderId . ' Update Timestamp...');
            $order = $this->restClient->updateOrder(['id' => $orderId, 'ownerId' => 17]);
            $second = 5;
            while ($second--) {
                Console::updateProgress($done, $total, $orderId . " {$order['updatedAt']} Sleeping {$second}...");
                sleep(1);
            }
        }
        Console::endProgress();
    }

    public function actionUpdateOrders($orderIds): void
    {
        $orderIds = array_filter(explode(',', $orderIds));
        $orderIdChunks = array_chunk($orderIds, 10);
        Console::startProgress($done = 0, $total = count($orderIds));
        foreach ($orderIdChunks as $orderIdChunk) {
            $batchRequest = $this->restClient->createBatchRequest();
            $orderIdChunkStr = implode(',', $orderIdChunk);
            foreach ($orderIdChunk as $orderId) {
                $batchRequest->updateOrder(['id' => $orderId, 'statusId' => 5.01]);
                Console::updateProgress(++$done, $total, $orderIdChunkStr . ' Preparing...');
            }
            Console::updateProgress($done, $total, $orderIdChunkStr . ' Requesting...');
            $batchRequest->send();
            $second = 10;
            while ($second--) {
                Console::updateProgress($done, $total, $orderIdChunkStr . " Sleeping {$second}...");
                sleep(1);
            }
        }
        Console::endProgress();
    }

    public function actionRevertBookedOrders($orderIds): void
    {
        $orderIds = array_filter(explode(',', $orderIds));
        $this->stdout("RevertOrderOutgoingStocks\n");
        $batchRequest = $this->restClient->createBatchRequest();
        foreach ($orderIds as $orderId) {
            $batchRequest->revertOrderOutgoingStocks(['id' => $orderId]);
        }
        $batchRequest->send();
        $this->stdout("UpdateOrder\n");
        $batchRequest = $this->restClient->createBatchRequest();
        foreach ($orderIds as $orderId) {
            $batchRequest->updateOrder(['id' => $orderId, 'statusId' => 5.72]);
        }
        $batchRequest->send();
    }

    /**
     * @throws InvalidConfigException
     * @throws \yii\authclient\InvalidResponseException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function actionAssignOrderItemVariations(): void
    {
        $orderItems = CsvHelper::readCsv(__DIR__ . '/../runtime/assignOrderItems.csv');
        usort($orderItems, static function ($a, $b) {
            return $a['order_item_id'] <=> $b['order_item_id'];
        });
        Console::startProgress($done = 0, $total = count($orderItems));
        foreach ($orderItems as $orderItem) {
            $prefix = "[Order:{$orderItem['order_id']}-{$orderItem['order_item_id']}][Variation{$orderItem['variation_no']}:{$orderItem['item_id']}-{$orderItem['variation_id']}";
            Console::updateProgress(++$done, $total, $prefix);
            $response = $this->adminClient->assignOrderItemLink(
                $orderItem['order_id'],
                $orderItem['order_item_id'],
                $orderItem['item_id'],
                $orderItem['variation_id'],
                strpos($orderItem['referrer_name'], 'FBA') !== false ? 105 : 108
            );
            VarDumper::dump($response->data);
        }
        Console::endProgress();
    }

    /**
     * @param string $pmOrderIds
     * @inheritdoc
     */
    public function actionGenerateInvoices(string $pmOrderIds): void
    {
        $client = $this->restClient;
        $pmOrderIds = array_filter(explode(',', $pmOrderIds));
        Console::startProgress($done = 0, $total = count($pmOrderIds));
        foreach ($pmOrderIds as $pmOrderId) {
            try {
                $response = $client->api("orders/{$pmOrderId}/documents/invoice/generate", 'POST');
                $message = strtr(VarDumper::dumpAsString($response), ["\n" => '']);
            } catch (InvalidResponseException $exception) {
                $message = $exception->getMessage();
            }
            Console::updateProgress(++$done, $total, $message);
        }
        Console::endProgress();
    }

    #endregion

    #region PM Item/Variation Update

    /**
     * @param $variationIds
     * @param int $warehouseId
     * @inheritdoc
     */
    public function actionCleanWarehouseLocationStocks($variationIds, int $warehouseId = 108): void
    {
        $variationIds = array_filter(explode(',', $variationIds));
        Console::startProgress($done = 0, $total = count($variationIds));
        $corrections = [];
        foreach ($variationIds as $variationId) {
            $locationStocks = $this->restClient->listWarehouseLocationStocks(['warehouseId' => $warehouseId, 'variationId' => $variationId]);
            foreach ($locationStocks['entries'] as $item) {
                $key = $item['variationId'] . '-' . $item['storageLocationId'];
                $corrections[$key] = [
                    "variationId" => $item['variationId'],
                    'reasonId' => 300,
                    "quantity" => 0,
                    "storageLocationId" => $item['storageLocationId'],
                ];
            }
            Yii::$app->cache->set(__METHOD__, $corrections);
            Console::updateProgress(++$done, $total);
        }
        $corrections = Yii::$app->cache->get(__METHOD__);
        VarDumper::dump($corrections);
        $this->restClient->correctStock([
            'warehouseId' => 108,
            'corrections' => array_values($corrections)
        ]);
        Console::endProgress();
    }

    #endregion

    #region PM Order/OrderItem Property Types

    /**
     * @param bool $format
     * @param string $lang
     * @inheritdoc
     */
    public function actionListOrderPropertyTypes(bool $format = true, string $lang = 'en'): void
    {
        $orderPropertyTypes = $this->restClient->listOrderPropertyTypes();
        if (!$format) {
            echo VarDumper::export($orderPropertyTypes);
            return;
        }
        $orderPropertyTypeIds = [];
        foreach ($orderPropertyTypes as $orderPropertyType) {
            $orderPropertyType['names'] = ArrayHelper::map($orderPropertyType['names'], 'lang', 'name');
            $name = $orderPropertyType['names'][$lang] ?? '';
            $name = strtr(strtoupper($name), [' ' => '_']);
            $orderPropertyTypeIds[$name] = $orderPropertyType['id'];
        }
        echo VarDumper::export($orderPropertyTypeIds);
    }

    #endregion

    /**
     * @throws InvalidConfigException
     * @throws \yii\httpclient\Exception
     * @inheritdoc
     */
    public function actionCheckOrderItemProperty44(): void
    {
        $pmClient = new PlentyMarketsRestClient([
            'apiBaseUrl' => Yii::$app->params['pm.url'],
            'username' => Yii::$app->params['pm.username'],
            'password' => Yii::$app->params['pm.password'],
        ]);
        $csvData = CsvHelper::readCsv(Yii::getAlias('@runtime/amazonOrders.csv'));
        $csvDataChunks = array_chunk($csvData, 20);
        foreach ($csvDataChunks as $csvDataChunk) {
            $orderTaxes = ArrayHelper::map($csvDataChunk, 'amazonOrderId', 'itemTax');
            $orderNos = array_keys($orderTaxes);
            $pmOrders = Yii::$app->getCache()->getOrSet(__METHOD__ . implode(',', $orderNos),
                static function () use ($pmClient, $orderNos) {
                    return $pmClient->getOrdersByExternalOrderNos($orderNos);
                }, 3600);
            $pmOrders = array_filter($pmOrders, static function ($pmOrder) {
                return $pmOrder['typeId'] === 1;
            });
            $pmOrders = array_map(static function ($pmOrder) {
                $pmOrder['properties'] = ArrayHelper::map($pmOrder['properties'], 'typeId', 'value');
                $pmOrder['orderItems'] = array_map(static function ($pmOrderItem) {
                    $pmOrderItem['properties'] = ArrayHelper::map($pmOrderItem['properties'], 'typeId', 'value');
                    return $pmOrderItem;
                }, $pmOrder['orderItems']);
                $pmOrder['orderItems'] = array_filter($pmOrder['orderItems'], static function ($pmOrderItem) {
                    return $pmOrderItem['itemVariationId'];
                });
                return $pmOrder;
            }, $pmOrders);
            foreach ($pmOrders as $pmOrder) {
                $orderNo = $pmOrder['properties'][PlentyMarketsConst::ORDER_PROPERTY_TYPE_IDS['EXTERNAL_ORDER_ID']];
                $inIncludeTax = (bool)$orderTaxes[$orderNo];
                foreach ($pmOrder['orderItems'] as $orderItem) {
                    $orderItemProperty44Value = $orderItem['properties'][44] ?? '';
                    $detail = [
                        'MATCH' => VarDumper::dumpAsString($inIncludeTax === (bool)$orderItemProperty44Value),
                        'OrderNo' => $orderNo,
                        'Tax' => $orderTaxes[$orderNo],
                        'orderItemVariationId' => $orderItem['itemVariationId'],
                        'price' => $orderItem['amounts'][0]['priceGross'],
                        'orderItemProperty44Value' => $orderItemProperty44Value,
                    ];
//                    print_r($detail);
                }
            }
        }
    }

    /**
     * @throws \yii\base\Exception
     * @inheritdoc
     */
    public function actionExportOrders(): void
    {
        $pmAdminClient = new PlentyMarketsAdminClient([
            'plentyId' => Yii::$app->params['pm.plentyId'],
            'username' => Yii::$app->params['pm.username'],
            'password' => Yii::$app->params['pm.password'],
            'requestOptions' => [
//                CURLOPT_PROXY => 'https://outhk.skylinktools.com',
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_TIMEOUT => 120,
            ],
        ]);

        $dir = Yii::getAlias('@uploads/downloads/pm');
        FileHelper::createDirectory($dir);
        Console::startProgress($done = 0, $total = 100);
        for ($done = 0; $done <= $total; $done++) {
            $fileContent = $pmAdminClient->dynamicExport('OrderCompleteAllField', $done * 6000);
            $file = 'orders_' . $done . '.csv';
            file_put_contents($dir . '/' . $file, $fileContent);
            Console::updateProgress($done, $total, "Download File {$file}");
        }
        Console::endProgress();
    }

    public function actionCheckItemFBA()
    {
        $data = [
            'amazonFbaPlatform' => 1,
            'isShippableByAmazon' => true,
        ];
        $itemIds = [
            9171, 433, 438, 457, 291, 293, 260, 242, 419, 328, 1238, 529, 515, 10403, 10404, 9083, 188, 10281, 542, 297, 541, 9157, 10440, 368, 623, 630, 10409, 10410, 9058, 176, 9029, 231, 241, 833, 10411, 10412, 9159, 624, 656, 799, 611, 9034, 9167, 14605, 10416, 10443, 10417, 10419, 10426, 456, 801, 9041, 9042, 16955, 9166, 10479, 10429, 10430, 9021, 9175, 16341, 516, 620, 614, 661, 10432, 10433, 16116, 10528, 10251, 13489, 10252, 17483, 18072, 14597, 10595, 16593, 13474, 13453, 13458, 13788, 14195, 14240, 13456, 13505, 13499, 13504, 13500, 13503, 13501, 16349, 16413, 17352, 18104, 10464, 12758, 13373, 14736, 13330, 15616, 13360, 10316, 13331, 13332, 15622, 15674, 13333, 13329, 12509, 13471, 10558, 10559, 13367, 14681, 13335, 14679, 17925, 17450, 17451, 13375, 13374, 10597, 12780, 15663, 10235, 13372, 9308, 18038, 9388, 18016, 13486, 14257, 10247, 10274, 10275, 10276, 9405, 13328, 14602, 10462, 13410, 13429, 13431, 13430, 13411, 10258, 10261, 10330, 10264, 10331, 14600, 17384, 17569, 14601, 14599, 10586, 15662, 16551, 17333, 14603, 16529, 18015, 16547, 16595, 18054, 18076
        ];
        $itemIdChunks = array_chunk($itemIds, 50);
        Console::startProgress($done = 0, $total = count($itemIdChunks));
        foreach ($itemIdChunks as $chunkItemIds) {
            $batchData = array_map(static function ($itemId) use ($data) {
                return array_merge($data, ['id' => $itemId]);
            }, $chunkItemIds);
            $this->restClient->api('/items', 'PUT', $batchData);
            Console::updateProgress(++$done, $total);
        }
        Console::endProgress();
    }
}
