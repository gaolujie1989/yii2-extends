<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd\helpers;

use lujie\dpd\constants\DpdConst;
use lujie\dpd\soap\Type\Address;
use lujie\dpd\soap\Type\GeneralShipmentData;
use lujie\dpd\soap\Type\PrintOption;
use lujie\dpd\soap\Type\PrintOptions;
use lujie\dpd\soap\Type\ProductAndServiceData;
use lujie\extend\models\AddressInterface;
use lujie\extend\models\ItemInterface;

/**
 * Class DPDSoapHelper
 * @package lujie\dpd\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DpdSoapHelper
{
    /**
     * @param string $outputFormat
     * @param string $paperFormat
     * @param bool $splitByParcel
     * @return PrintOptions
     * @inheritdoc
     */
    public static function createPrintOptions(string $outputFormat = 'PDF', string $paperFormat = 'A4', bool $splitByParcel = true): PrintOptions
    {
        $printOption = new PrintOption([
            'outputFormat' => $outputFormat,
            'paperFormat' => $paperFormat,
        ]);
        return new PrintOptions([
            'printOption' => $printOption,
            'splitByParcel' => $splitByParcel,
        ]);
    }

    /**
     * @param ItemInterface $item
     * @param AddressInterface $sender
     * @param AddressInterface $recipient
     * @param string $id
     * @param array $refs
     * @return GeneralShipmentData
     * @inheritdoc
     */
    public static function createGeneralShipmentData(ItemInterface $item, AddressInterface $sender, AddressInterface $recipient, string $id, array $refs = []): GeneralShipmentData
    {
        $volumeCm3 = (int)round($item->getLengthMM() * $item->getWidthMM() * $item->getHeightMM() / 1000);
        return new GeneralShipmentData([
            'mpsCustomerReferenceNumber1' => $refs ? array_shift($refs) : '',
            'mpsCustomerReferenceNumber2' => $refs ? array_shift($refs) : '',
            'mpsCustomerReferenceNumber3' => $refs ? array_shift($refs) : '',
            'mpsCustomerReferenceNumber4' => $refs ? array_shift($refs) : '',
            'identificationNumber' => $id,
            'sendingDepot' => '0998', //'0163', //???不知道
            'product' => DpdConst::PRODUCT_DPD_CLASSIC,
            'mpsCompleteDelivery' => false,
            'mpsCompleteDeliveryLabel' => false,
            'mpsVolume' => $volumeCm3,
            'mpsWeight' => (int)round($item->getWeightG() / 10),
            'mpsExpectedSendingDate' => date('Ymd'),
            'mpsExpectedSendingTime' => '170000',
            'sender' => static::createDpdAddress($sender),
            'recipient' => static::createDpdAddress($recipient),
        ]);
    }

    /**
     * @param string $orderType
     * @return ProductAndServiceData
     * @inheritdoc
     */
    public static function createProductAndServiceData(string $orderType = DpdConst::ORDER_TYPE_CONSIGNMENT): ProductAndServiceData
    {
        return new ProductAndServiceData([
            'orderType' => $orderType,
            'saturdayDelivery' => false,
            'exWorksDelivery' => false,
            'guarantee' => false,
            'tyres' => false,
            'food' => false
        ]);
    }

    /**
     * @param AddressInterface $address
     * @return Address
     * @inheritdoc
     */
    public static function createDpdAddress(AddressInterface $address): Address
    {
        return new Address([
            'name1' => $address->getFirstName(),
            'name2' => $address->getLastName(),
            'street' => $address->getState(),
            'houseNo' => $address->getStreetNo(),
            'state' => $address->getState(),
            'country' => $address->getCountry(),
            'zipCode' => $address->getPostalCode(),
            'city' => $address->getCity(),
            'customerNumber' => $address->getPhone(),
            'contact' => $address->getCompanyName(),
            'phone' => $address->getPhone(),
            'email' => $address->getEmail(),
            'comment' => $address->getAdditional(),
        ]);
    }
}