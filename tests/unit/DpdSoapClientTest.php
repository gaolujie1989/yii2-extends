<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd\tests\unit;


use dpd\Type\Address;
use dpd\Type\GeneralShipmentData;
use dpd\Type\GetTrackingData;
use dpd\Type\HigherInsurance;
use dpd\Type\Parcel;
use dpd\Type\PrintOptions;
use dpd\Type\ProductAndServiceData;
use dpd\Type\ShipmentServiceData;
use dpd\Type\StoreOrders;
use lujie\dpd\DpdSoapClient;
use Yii;

class DpdSoapClientTest extends \Codeception\Test\Unit
{
    /**
     * @var \UnitTester
     */
    protected $tester;

    protected function _before()
    {
    }

    protected function _after()
    {
    }

    public function testStoreOrder()
    {
        $client = new DpdSoapClient();
        $printOptions = new PrintOptions('PDF', 'A4', null, null);
        $generalShipmentData = new GeneralShipmentData(null, null, 's_ref_305-6470832-3224327', null, null, null, 'id_305-6470832-3224327',
            '0998', 'CL', false, false, null, 2280, date('Ymd', strtotime('+1 day')), '170000',
            $this->getSenderAddress(), $this->getRecipientAddress());
        $higherInsurance = new HigherInsurance(0, 'EUR');
        $parcels = new Parcel(null, 'p_ref_305-6470832-3224327', null, null, null, false, null, 2280, false, $higherInsurance, 'TEST Content',
            null, 1, null, null, null, null, null, true, 'note1', 'note2', null);
        $personalDelivery = null;
        $pickup = null;
        $parcelShopDelivery = null;
        $productAndServiceData = new ProductAndServiceData('consignment', false, false, false, false,
            null, null, null, null, null, null, null, null, null);
        $order = new ShipmentServiceData($generalShipmentData, $parcels, $productAndServiceData);
        $storeOrdersResponse = $client->storeOrders(new StoreOrders($printOptions, $order));
        $faults = $storeOrdersResponse->getOrderResult()->getShipmentResponses()[0]->getFaults();
        $this->assertEmpty($faults, $faults[0]->getFaultCode() . ': ' . $faults[0]->getMessage());
    }

    public function te1stGetTrackingData()
    {
        $client = new DpdSoapClient();
        $getTrackingDataResponse = $client->getTrackingData(new GetTrackingData('01405400945058'));
        $this->assertNotEmpty($getTrackingDataResponse->getTrackingresult()->getStatusInfo());
    }

    protected function getRecipientAddress(): Address
    {
        return new Address(
            'Anas', 'Younes',
            'Gagarinstraße', '11',
            null, 'DE', '19063', 'Schwerin',
            null, null, null, '01590 1487281', null, null, null, null);
    }

    protected function getSenderAddress(): Address
    {
        return new Address(
            'CCLife Technic GmbH', 'Xiaomeng Bian',
            'Barentsstr', '15',
            null, 'DE', '53881', 'Euskirchen, Großbüllesheim',
            null, null, null, '021-36411196', '021-36411196', 'cc@xxx.com', null, null);
    }
}
