<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd\tests\unit;

use dpd\Type\Address;
use dpd\Type\GeneralShipmentData;
use dpd\Type\GetTrackingData;
use dpd\Type\PrintOptions;
use dpd\Type\ProductAndServiceData;
use dpd\Type\ShipmentServiceData;
use dpd\Type\StoreOrders;
use lujie\dpd\DpdSoapClient;
use Yii;

class DpdSoapClientTest extends \Codeception\Test\Unit
{
    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testStoreOrder(): void
    {
        $client = new DpdSoapClient();
        $printOptions = new PrintOptions('PDF', 'A4', null, null);
        $generalShipmentData = new GeneralShipmentData(
            null,
            null,
            's_ref_305-6470832-3224327',
            null,
            null,
            null,
            'id_305-6470832-3224327',
            '0998',
            'CL',
            false,
            false,
            null,
            2280,
            date('Ymd', strtotime('+1 day')),
            '170000',
            $this->getSenderAddress(),
            $this->getRecipientAddress()
        );
        $productAndServiceData = new ProductAndServiceData(
            'consignment',
            false,
            false,
            false,
            false,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null,
            null
        );
        $order = new ShipmentServiceData($generalShipmentData, null, $productAndServiceData);
        $storeOrdersResponse = $client->storeOrders(new StoreOrders($printOptions, $order));
        $shipmentResponse = $storeOrdersResponse->getOrderResult()->getShipmentResponses()[0];
        $faults = $shipmentResponse->getFaults();
        $this->assertEmpty($faults);
        $parcelLabelNumber = $shipmentResponse->getParcelInformation()[0]->getParcelLabelNumber();
        file_put_contents(Yii::getAlias('@runtime/dpd_') . $parcelLabelNumber . '.pdf', $storeOrdersResponse->getOrderResult()->getParcellabelsPDF());
    }

    public function te1stGetTrackingData(): void
    {
        $client = new DpdSoapClient();
        $getTrackingDataResponse = $client->getTrackingData(new GetTrackingData('01405400945058'));
        $this->assertNotEmpty($getTrackingDataResponse->getTrackingresult()->getStatusInfo());
    }

    protected function getRecipientAddress(): Address
    {
        return new Address(
            'Anas',
            'Younes',
            'Gagarinstraße',
            '11',
            null,
            'DE',
            '19063',
            'Schwerin',
            null,
            null,
            null,
            '01590 1487281',
            null,
            null,
            null,
            null
        );
    }

    protected function getSenderAddress(): Address
    {
        return new Address(
            'CCLife Technic GmbH',
            'Xiaomeng Bian',
            'Barentsstr',
            '15',
            null,
            'DE',
            '53881',
            'Euskirchen, Großbüllesheim',
            null,
            null,
            null,
            '021-36411196',
            '021-36411196',
            'cc@xxx.com',
            null,
            null
        );
    }
}
