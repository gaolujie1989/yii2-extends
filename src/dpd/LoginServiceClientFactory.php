<?php

namespace dpd;

use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;
use Symfony\Component\EventDispatcher\EventDispatcher;

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

