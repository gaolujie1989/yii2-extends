<?php

namespace dpd;

use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;
use Symfony\Component\EventDispatcher\EventDispatcher;

class ParcelShopFinderServiceClientFactory
{
    public static function factory(string $wsdl) : \dpd\ParcelShopFinderServiceClient
    {
        $engine = ExtSoapEngineFactory::fromOptions(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(ParcelShopFinderServiceClassmap::getCollection())
        );
        $eventDispatcher = new EventDispatcher();

        return new ParcelShopFinderServiceClient($engine, $eventDispatcher);
    }
}
