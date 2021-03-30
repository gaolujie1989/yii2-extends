<?php

namespace dpd;

use dpd\Type;
use Phpro\SoapClient\Soap\ClassMap\ClassMap;
use Phpro\SoapClient\Soap\ClassMap\ClassMapCollection;

class ParcelLifeCycleServiceClassmap
{
    public static function getCollection() : \Phpro\SoapClient\Soap\ClassMap\ClassMapCollection
    {
        return new ClassMapCollection([
            new ClassMap('getTrackingData', Type\GetTrackingData::class),
            new ClassMap('getTrackingDataResponse', Type\GetTrackingDataResponse::class),
            new ClassMap('getParcelLabelNumberForWebNumber', Type\GetParcelLabelNumberForWebNumber::class),
            new ClassMap('getParcelLabelNumberForWebNumberResponse', Type\GetParcelLabelNumberForWebNumberResponse::class),
            new ClassMap('TrackingResult', Type\TrackingResult::class),
            new ClassMap('ContentLine', Type\ContentLine::class),
            new ClassMap('ContentItem', Type\ContentItem::class),
            new ClassMap('StatusInfo', Type\StatusInfo::class),
            new ClassMap('ShipmentInfo', Type\ShipmentInfo::class),
            new ClassMap('TrackingProperty', Type\TrackingProperty::class),
            new ClassMap('DataFault', Type\DataFault::class),
            new ClassMap('SystemFault', Type\SystemFault::class),
            new ClassMap('Fault', Type\Fault::class),
            new ClassMap('authentication', Type\Authentication::class),
            new ClassMap('authenticationFault', Type\AuthenticationFault::class),
        ]);
    }
}
