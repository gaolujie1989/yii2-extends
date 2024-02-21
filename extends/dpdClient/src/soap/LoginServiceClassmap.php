<?php

namespace lujie\dpd\soap;

use lujie\dpd\soap\Type;
use Soap\ExtSoapEngine\Configuration\ClassMap\ClassMapCollection;
use Soap\ExtSoapEngine\Configuration\ClassMap\ClassMap;

class LoginServiceClassmap
{
    public static function getCollection() : \Soap\ExtSoapEngine\Configuration\ClassMap\ClassMapCollection
    {
        return new ClassMapCollection(
            new ClassMap('Login', Type\Login::class),
            new ClassMap('LoginException', Type\LoginException::class),
            new ClassMap('getAuth', Type\GetAuth::class),
            new ClassMap('getAuthResponse', Type\GetAuthResponse::class),
            new ClassMap('authentication', Type\Authentication::class),
            new ClassMap('authenticationFault', Type\AuthenticationFault::class),
        );
    }
}

