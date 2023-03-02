<?php

namespace lujie\dpd\soap;

use lujie\dpd\soap\ParcelShopFinderServiceClient;
use lujie\dpd\soap\ParcelShopFinderServiceClassmap;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;

class ParcelShopFinderServiceClientFactory
{

    public static function factory(string $wsdl) : \lujie\dpd\soap\ParcelShopFinderServiceClient
    {
        $engine = ExtSoapEngineFactory::fromOptions(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(ParcelShopFinderServiceClassmap::getCollection())
        );
        $eventDispatcher = new EventDispatcher();

        return new ParcelShopFinderServiceClient($engine, $eventDispatcher);
    }


}

