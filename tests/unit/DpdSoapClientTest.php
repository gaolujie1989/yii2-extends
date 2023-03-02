<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd\tests\unit;

use lujie\dpd\DpdSoapClient;
use lujie\dpd\helpers\DpdSoapHelper;
use lujie\dpd\soap\Type\ShipmentServiceData;
use lujie\dpd\soap\Type\StoreOrders;
use lujie\extend\models\Item;
use Yii;

/**
 * Class DpdSoapClientTest
 * @package lujie\dpd\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DpdSoapClientTest extends \Codeception\Test\Unit
{
    /**
     * @inheritdoc
     */
    public function testStoreOrder(): void
    {
        $item = new Item([
            'weightG' => 12000,
            'lengthMM' => 760,
            'widthMM' => 540,
            'heightMM' => 320,
        ]);
        $warehouseAddress = new \lujie\extend\models\Address([
            'firstName' => 'CCLife Technic GmbH',
            'lastName' => 'Xiaomeng Bian',
            'country' => 'DE',
            'city' => 'Euskirchen, Großbüllesheim',
            'postalCode' => '53881',
            'street' => 'Barentsstr',
            'streetNo' => '15',
            'email' => 'yingjun.wang@cclife.de',
            'phone' => '021-36412196',
        ]);
        $customerAddress = new \lujie\extend\models\Address([
            'firstName' => 'Anas',
            'lastName' => 'Younes',
            'country' => 'DE',
            'city' => 'Schwerin',
            'postalCode' => '19063',
            'street' => 'Gagarinstraße',
            'streetNo' => '11',
            'phone' => '01590 1487281',
        ]);

        $generalShipmentData = DpdSoapHelper::createGeneralShipmentData($item, $warehouseAddress, $customerAddress, 'id-xxx', ['ref-xxx']);
        $order = new ShipmentServiceData([
            'generalShipmentData' => $generalShipmentData,
            'productAndServiceData' => DpdSoapHelper::createProductAndServiceData(),
        ]);

        $client = new DpdSoapClient();
        $storeOrders = new StoreOrders();
        $storeOrders->setPrintOptions(DpdSoapHelper::createPrintOptions());
        $storeOrders->setOrder($order);

        $storeOrdersResponse = $client->storeOrders($storeOrders);
        codecept_debug($storeOrdersResponse);
        $shipmentResponse = $storeOrdersResponse->getOrderResult()->getShipmentResponses();
        $faults = $shipmentResponse->getFaults();
        $this->assertEmpty($faults);
        $parcelLabelNumber = $shipmentResponse->getParcelInformation()->getParcelLabelNumber();
        file_put_contents(Yii::getAlias('@runtime/dpd_') . $parcelLabelNumber . '.pdf', $storeOrdersResponse->getOrderResult()->getParcellabelsPDF());
    }
}
