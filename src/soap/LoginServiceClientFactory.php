<?php

namespace lujie\dpd\soap;

use lujie\dpd\soap\LoginServiceClient;
use lujie\dpd\soap\LoginServiceClassmap;
use Symfony\Component\EventDispatcher\EventDispatcher;
use Phpro\SoapClient\Soap\DefaultEngineFactory;
use Soap\ExtSoapEngine\ExtSoapOptions;
use Phpro\SoapClient\Caller\EventDispatchingCaller;
use Phpro\SoapClient\Caller\EngineCaller;

class LoginServiceClientFactory
{
    public static function factory(string $wsdl) : \lujie\dpd\soap\LoginServiceClient
    {
        $engine = DefaultEngineFactory::create(
            ExtSoapOptions::defaults($wsdl, [])
                ->withClassMap(LoginServiceClassmap::getCollection())
        );

        $eventDispatcher = new EventDispatcher();
        $caller = new EventDispatchingCaller(new EngineCaller($engine), $eventDispatcher);

        return new LoginServiceClient($caller);
    }
}

