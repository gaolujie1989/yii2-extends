<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\dpd\helpers;

use lujie\dpd\constants\DpdConst;
use lujie\dpd\soap\Type\Address;
use lujie\dpd\soap\Type\AddressWithType;
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
    public static function createPrintOptions(string $outputFormat = 'PDF', string $paperFormat = 'A6', bool $splitByParcel = true): PrintOptions
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
     * @param AddressInterface $sender
     * @param string $senderAddressType
     * @param AddressInterface $recipient
     * @param string $recipientAddressType
     * @param string $sendingDepot
     * @param string $id
     * @param array $refs
     * @param ItemInterface|null $item
     * @return GeneralShipmentData
     * @inheritdoc
     */
    public static function createGeneralShipmentData(
        AddressInterface $sender,
        string $senderAddressType,
        AddressInterface $recipient,
        string $recipientAddressType,
        string $sendingDepot,
        string $id,
        array $refs = [],
        ?ItemInterface $item = null
    ): GeneralShipmentData
    {
//        $volumeCm3 = (int)round($item->getLengthMM() * $item->getWidthMM() * $item->getHeightMM() / 1000);
//        $weight10G = (int)round($item->getWeightG() / 10);
        return new GeneralShipmentData([
            'mpsCustomerReferenceNumber1' => $refs ? array_shift($refs) : '',
            'mpsCustomerReferenceNumber2' => $refs ? array_shift($refs) : '',
            'mpsCustomerReferenceNumber3' => $refs ? array_shift($refs) : '',
            'mpsCustomerReferenceNumber4' => $refs ? array_shift($refs) : '',
            'identificationNumber' => $id,
            'sendingDepot' => $sendingDepot,
            'product' => DpdConst::PRODUCT_DPD_CLASSIC,
            'mpsCompleteDelivery' => false,
            'mpsCompleteDeliveryLabel' => false,
//            'mpsVolume' => 120000,
//            'mpsWeight' => 120,
            'mpsExpectedSendingDate' => date('Ymd'),
            'mpsExpectedSendingTime' => '160000',
            'sender' => static::createDpdAddress($sender, $senderAddressType),
            'recipient' => static::createDpdAddress($recipient, $recipientAddressType),
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
    public static function createDpdAddress(AddressInterface $address, string $addressType): AddressWithType
    {
        return new AddressWithType(array_filter([
            'addressType' => $addressType,
            'name1' => $address->getFirstName(),
            'name2' => $address->getLastName(),
            'street' => $address->getStreet(),
            'houseNo' => $address->getStreetNo(),
            'state' => $address->getState(),
            'country' => $address->getCountry(),
            'zipCode' => $address->getPostalCode(),
            'city' => $address->getCity(),
            'customerNumber' => preg_replace('/\D/', '', $address->getPhone()),
            'contact' => $address->getCompanyName(),
            'phone' => $address->getPhone(),
            'email' => $address->getEmail(),
            'comment' => $address->getAdditional(),
        ]));
    }
}
