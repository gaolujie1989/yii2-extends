<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd\tests\unit;

use lujie\dpd\constants\DpdConst;
use lujie\dpd\DpdSoapClient;
use lujie\dpd\helpers\DpdSoapHelper;
use lujie\dpd\soap\Type\ShipmentServiceData;
use lujie\dpd\soap\Type\StoreOrders;
use lujie\extend\models\Address;
use lujie\extend\models\AddressInterface;
use lujie\extend\models\Item;
use lujie\extend\test\MockTransportHelper;
use Yii;
use yii\helpers\VarDumper;
use yii\httpclient\CurlTransport;
use yii\httpclient\MockTransport;

/**
 * Class DpdSoapClientTest
 * @package lujie\dpd\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DpdSoapClientTest extends \Codeception\Test\Unit
{
    /**
     * @throws \yii\base\InvalidConfigException
     * @throws \yii\base\UserException
     * @inheritdoc
     */
    public function testStoreOrder(): void
    {
        $client = new DpdSoapClient([
//            'username' => Yii::$app->params['dpd.username'],
//            'password' => Yii::$app->params['dpd.password'],
        ]);
        $client->setSandbox();
//        $client->cache = false;
        $client->httpHandler->getHttpClient()->setTransport(new CurlTransport());

        $parcel = $client->createSingleParcel(
            $this->getWarehouseAddress(),
            DpdConst::ADDRESS_TYPE_COMMERCIAL,
            $this->getCustomerAddress(),
            DpdConst::ADDRESS_TYPE_PRIVATE,
            'id-123456789',
            [
                'ref1-123456789',
                'ref2-123456789',
            ],
        );
        $parcelLabelNumber = $parcel->getParcelLabelNumber();
        $this->assertNotEmpty($parcelLabelNumber);
        $output = $parcel->getOutput()[0];
        $labelFilePath = Yii::getAlias('@runtime/dpd_') . $parcelLabelNumber . '.' . $output->getFormat();
        $this->assertNotEmpty($output->getContent());
        file_put_contents($labelFilePath, $output->getContent());
    }

    /**
     * @return AddressInterface
     * @inheritdoc
     */
    protected function getWarehouseAddress(): AddressInterface
    {
        return new Address([
            'firstName' => 'CCLife Technic GmbH',
            'lastName' => 'Yingjun Wang',
            'country' => 'DE',
            'city' => 'Euskirchen, Großbüllesheim',
            'postalCode' => '53881',
            'street' => 'Barentsstr',
            'streetNo' => '15',
            'email' => 'yingjun.wang@cclife.de',
            'phone' => '021-36412196',
        ]);
    }

    /**
     * @return AddressInterface
     * @inheritdoc
     */
    protected function getCustomerAddress(): AddressInterface
    {
        return new Address([
            'firstName' => 'Anas',
            'lastName' => 'Younes',
            'country' => 'DE',
            'city' => 'Schwerin',
            'postalCode' => '19063',
            'street' => 'Gagarinstraße',
            'streetNo' => '11',
            'phone' => '01590 1487281',
        ]);
    }
}
