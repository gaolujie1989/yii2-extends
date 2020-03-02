<?php

namespace dpd;

use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ShipmentServiceClientFactory
{

    public static function factory(string $wsdl) : \dpd\ShipmentServiceClient
    {
        $engine = ExtSoapEngineFactory::fromOptions(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(ShipmentServiceClassmap::getCollection())
        );
        $eventDispatcher = new EventDispatcher();

        return new ShipmentServiceClient($engine, $eventDispatcher);
    }


}

