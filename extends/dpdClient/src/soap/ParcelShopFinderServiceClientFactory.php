<?php

namespace lujie\dpd\soap;

use lujie\dpd\soap\ParcelShopFinderServiceClient;
use lujie\dpd\soap\ParcelShopFinderServiceClassmap;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Phpro\SoapClient\Soap\DefaultEngineFactory;
use Soap\ExtSoapEngine\ExtSoapOptions;
use Phpro\SoapClient\Caller\EventDispatchingCaller;
use Phpro\SoapClient\Caller\EngineCaller;

class ParcelShopFinderServiceClientFactory
{
    public static function factory(string $wsdl) : \lujie\dpd\soap\ParcelShopFinderServiceClient
    {
        $engine = DefaultEngineFactory::create(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(ParcelShopFinderServiceClassmap::getCollection())
        );

        $eventDispatcher = new EventDispatcher();
        $caller = new EventDispatchingCaller(new EngineCaller($engine), $eventDispatcher);

        return new ParcelShopFinderServiceClient($caller);
    }
}

