<?php

namespace dpd;

use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ParcelLifeCycleServiceClientFactory
{

    public static function factory(string $wsdl) : \dpd\ParcelLifeCycleServiceClient
    {
        $engine = ExtSoapEngineFactory::fromOptions(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(ParcelLifeCycleServiceClassmap::getCollection())
        );
        $eventDispatcher = new EventDispatcher();

        return new ParcelLifeCycleServiceClient($engine, $eventDispatcher);
    }


}

