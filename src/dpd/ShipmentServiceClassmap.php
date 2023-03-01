<?php

namespace dpd;

use dpd\Type;
use Phpro\SoapClient\Soap\ClassMap\ClassMapCollection;
use Phpro\SoapClient\Soap\ClassMap\ClassMap;

class ShipmentServiceClassmap
{

    public static function getCollection() : \Phpro\SoapClient\Soap\ClassMap\ClassMapCollection
    {
        return new ClassMapCollection([
            new ClassMap('storeOrders', Type\StoreOrders::class),
            new ClassMap('storeOrdersResponse', Type\StoreOrdersResponse::class),
            new ClassMap('storeOrdersResponseType', Type\StoreOrdersResponseType::class),
            new ClassMap('shipmentResponse', Type\ShipmentResponse::class),
            new ClassMap('shipmentServiceData', Type\ShipmentServiceData::class),
            new ClassMap('generalShipmentData', Type\GeneralShipmentData::class),
            new ClassMap('address', Type\Address::class),
            new ClassMap('addressWithBusinessUnit', Type\AddressWithBusinessUnit::class),
            new ClassMap('addressWithType', Type\AddressWithType::class),
            new ClassMap('parcel', Type\Parcel::class),
            new ClassMap('productAndServiceData', Type\ProductAndServiceData::class),
            new ClassMap('international', Type\International::class),
            new ClassMap('delivery', Type\Delivery::class),
            new ClassMap('proactiveNotification', Type\ProactiveNotification::class),
            new ClassMap('notification', Type\Notification::class),
            new ClassMap('hazardous', Type\Hazardous::class),
            new ClassMap('personalDelivery', Type\PersonalDelivery::class),
            new ClassMap('pickup', Type\Pickup::class),
            new ClassMap('parcelInformationType', Type\ParcelInformationType::class),
            new ClassMap('faultCodeType', Type\FaultCodeType::class),
            new ClassMap('higherInsurance', Type\HigherInsurance::class),
            new ClassMap('parcelShopDelivery', Type\ParcelShopDelivery::class),
            new ClassMap('printOptions', Type\PrintOptions::class),
            new ClassMap('printOption', Type\PrintOption::class),
            new ClassMap('printer', Type\Printer::class),
            new ClassMap('additionalInvoiceLine', Type\AdditionalInvoiceLine::class),
            new ClassMap('OutputType', Type\OutputType::class),
            new ClassMap('authentication', Type\Authentication::class),
            new ClassMap('authenticationFault', Type\AuthenticationFault::class),
        ]);
    }


}

