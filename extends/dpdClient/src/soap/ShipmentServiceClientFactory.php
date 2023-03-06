<?php

namespace lujie\dpd\soap;

use lujie\dpd\soap\ShipmentServiceClient;
use lujie\dpd\soap\ShipmentServiceClassmap;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;

class ShipmentServiceClientFactory
{

    public static function factory(string $wsdl) : \lujie\dpd\soap\ShipmentServiceClient
    {
        $engine = ExtSoapEngineFactory::fromOptions(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(ShipmentServiceClassmap::getCollection())
        );
        $eventDispatcher = new EventDispatcher();

        return new ShipmentServiceClient($engine, $eventDispatcher);
    }


}

