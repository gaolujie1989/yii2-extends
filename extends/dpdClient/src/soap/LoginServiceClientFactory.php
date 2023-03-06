<?php

namespace lujie\dpd\soap;

use lujie\dpd\soap\LoginServiceClient;
use lujie\dpd\soap\LoginServiceClassmap;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapEngineFactory;
use Phpro\SoapClient\Soap\Driver\ExtSoap\ExtSoapOptions;

class LoginServiceClientFactory
{

    public static function factory(string $wsdl) : \lujie\dpd\soap\LoginServiceClient
    {
        $engine = ExtSoapEngineFactory::fromOptions(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(LoginServiceClassmap::getCollection())
        );
        $eventDispatcher = new EventDispatcher();

        return new LoginServiceClient($engine, $eventDispatcher);
    }


}

