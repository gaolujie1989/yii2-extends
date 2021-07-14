<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tests\unit\f4px;

use lujie\fulfillment\BaseFulfillmentService;
use lujie\fulfillment\common\Address;
use lujie\fulfillment\common\Item;
use lujie\fulfillment\common\ItemBarcode;
use lujie\fulfillment\common\Order;
use lujie\fulfillment\common\OrderItem;
use lujie\fulfillment\constants\FulfillmentConst;
use lujie\fulfillment\f4px\F4pxClient;
use lujie\fulfillment\f4px\F4pxFulfillmentService;
use lujie\fulfillment\models\FulfillmentAccount;
use lujie\fulfillment\models\FulfillmentItem;
use lujie\fulfillment\models\FulfillmentOrder;
use lujie\fulfillment\models\FulfillmentWarehouse;
use lujie\fulfillment\models\FulfillmentWarehouseStock;
use lujie\fulfillment\models\FulfillmentWarehouseStockMovement;
use lujie\fulfillment\tests\unit\mocks\MockF4pxClient;
use lujie\fulfillment\tests\unit\mocks\MockFulfillmentDataLoader;
use PHPUnit\Framework\Assert;
use Yii;
use yii\base\BaseObject;

class F4pxClientTest extends \Codeception\Test\Unit
{
    /**
     * @return BaseFulfillmentService
     * @inheritdoc
     */
    protected function getClient(): F4pxClient
    {
        return new F4pxClient([
            'appKey' => Yii::$app->params['f4px.appKey'],
            'appSecret' => Yii::$app->params['f4px.appSecret'],
            'sandbox' => false,
        ]);
    }

    public function testGetBilling(): void
    {
        $f4pxClient = $this->getClient();
        $billing = $f4pxClient->getBilling(['order_no' => 'OC9201342107140045', 'business_type' => 'O']);
        Assert::assertNotNull($billing);
        codecept_debug($billing);
    }

    public function testGetOutbound(): void
    {
        $f4pxClient = $this->getClient();
        $outboundList = $f4pxClient->getOutboundList(['consignment_no' => 'OC9201342107140045']);
        Assert::assertNotNull($outboundList);
        codecept_debug($outboundList);
    }
}
