<?php

namespace lujie\dpd\soap;

use lujie\dpd\soap\ShipmentServiceClient;
use lujie\dpd\soap\ShipmentServiceClassmap;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Phpro\SoapClient\Soap\DefaultEngineFactory;
use Soap\ExtSoapEngine\ExtSoapOptions;
use Phpro\SoapClient\Caller\EventDispatchingCaller;
use Phpro\SoapClient\Caller\EngineCaller;

class ShipmentServiceClientFactory
{
    public static function factory(string $wsdl) : \lujie\dpd\soap\ShipmentServiceClient
    {
        $engine = DefaultEngineFactory::create(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(ShipmentServiceClassmap::getCollection())
        );

        $eventDispatcher = new EventDispatcher();
        $caller = new EventDispatchingCaller(new EngineCaller($engine), $eventDispatcher);

        return new ShipmentServiceClient($caller);
    }
}

