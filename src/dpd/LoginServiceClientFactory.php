<?php

namespace dpd;

use dpd\LoginServiceClient;
use dpd\LoginServiceClassmap;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;

class LoginServiceClientFactory
{

    public static function factory(string $wsdl) : \dpd\LoginServiceClient
    {
        $engine = ExtSoapEngineFactory::fromOptions(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(LoginServiceClassmap::getCollection())
        );
        $eventDispatcher = new EventDispatcher();

        return new LoginServiceClient($engine, $eventDispatcher);
    }


}

