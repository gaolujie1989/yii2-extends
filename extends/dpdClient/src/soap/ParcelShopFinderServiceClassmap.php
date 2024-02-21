<?php

namespace lujie\dpd\soap;

use lujie\dpd\soap\Type;
use Soap\ExtSoapEngine\Configuration\ClassMap\ClassMapCollection;
use Soap\ExtSoapEngine\Configuration\ClassMap\ClassMap;

class ParcelShopFinderServiceClassmap
{
    public static function getCollection() : \Soap\ExtSoapEngine\Configuration\ClassMap\ClassMapCollection
    {
        return new ClassMapCollection(
            new ClassMap('FindParcelShopsType', Type\FindParcelShopsType::class),
            new ClassMap('FindParcelShopsResponseType', Type\FindParcelShopsResponseType::class),
            new ClassMap('FindParcelShopsByGeoDataType', Type\FindParcelShopsByGeoDataType::class),
            new ClassMap('FindParcelShopsByGeoDataResponseType', Type\FindParcelShopsByGeoDataResponseType::class),
            new ClassMap('FindCitiesType', Type\FindCitiesType::class),
            new ClassMap('FindCitiesResponseType', Type\FindCitiesResponseType::class),
            new ClassMap('GetAvailableServicesType', Type\GetAvailableServicesType::class),
            new ClassMap('GetAvailableServicesResponseType', Type\GetAvailableServicesResponseType::class),
            new ClassMap('FaultType', Type\FaultType::class),
            new ClassMap('DataFaultType', Type\DataFaultType::class),
            new ClassMap('SystemFaultType', Type\SystemFaultType::class),
            new ClassMap('ParcelShopType', Type\ParcelShopType::class),
            new ClassMap('CityType', Type\CityType::class),
            new ClassMap('ServicesRequestType', Type\ServicesRequestType::class),
            new ClassMap('ServiceRequestType', Type\ServiceRequestType::class),
            new ClassMap('ServiceDetailRequestType', Type\ServiceDetailRequestType::class),
            new ClassMap('ServicesType', Type\ServicesType::class),
            new ClassMap('ServiceType', Type\ServiceType::class),
            new ClassMap('ServiceDetailType', Type\ServiceDetailType::class),
            new ClassMap('HolidayType', Type\HolidayType::class),
            new ClassMap('OpeningHoursType', Type\OpeningHoursType::class),
            new ClassMap('authentication', Type\Authentication::class),
            new ClassMap('authenticationFault', Type\AuthenticationFault::class),
        );
    }
}

