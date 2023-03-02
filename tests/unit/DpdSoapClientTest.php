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
use lujie\extend\models\Item;
use Yii;
use yii\helpers\VarDumper;

/**
 * Class DpdSoapClientTest
 * @package lujie\dpd\tests\unit
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DpdSoapClientTest extends \Codeception\Test\Unit
{
    /**
     * @throws \yii\base\UserException
     * @inheritdoc
     */
    public function testStoreOrder(): void
    {
        $warehouseAddress = new Address([
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
        $customerAddress = new Address([
            'firstName' => 'Anas',
            'lastName' => 'Younes',
            'country' => 'DE',
            'city' => 'Schwerin',
            'postalCode' => '19063',
            'street' => 'Gagarinstraße',
            'streetNo' => '11',
            'phone' => '01590 1487281',
        ]);

        $client = new DpdSoapClient([
//            'username' => Yii::$app->params['dpd.username'],
//            'password' => Yii::$app->params['dpd.password'],
        ]);
        $client->setSandbox();

        $parcel = $client->createSingleParcel(
            $warehouseAddress,
            DpdConst::ADDRESS_TYPE_COMMERCIAL,
            $customerAddress,
            DpdConst::ADDRESS_TYPE_PRIVATE,
            'id-123456789',
            [
                'ref1-123456789',
                'ref2-123456789',
            ],
        );
        $parcelLabelNumber = $parcel->getParcelLabelNumber();
        codecept_debug($parcelLabelNumber);
        codecept_debug($parcel->getDpdReference());
        $output = $parcel->getOutput()[0];
        $labelFilePath = Yii::getAlias('@runtime/dpd_') . $parcelLabelNumber . '.' . $output->getFormat();
        file_put_contents($labelFilePath, $output->getContent());
    }
}
