<?php

namespace dpd;

use dpd\Type;
use Phpro\SoapClient\Soap\ClassMap\ClassMap;
use Phpro\SoapClient\Soap\ClassMap\ClassMapCollection;

class LoginServiceClassmap
{
    public static function getCollection() : \Phpro\SoapClient\Soap\ClassMap\ClassMapCollection
    {
        return new ClassMapCollection([
            new ClassMap('Login', Type\Login::class),
            new ClassMap('LoginException', Type\LoginException::class),
            new ClassMap('getAuth', Type\GetAuth::class),
            new ClassMap('getAuthResponse', Type\GetAuthResponse::class),
            new ClassMap('authentication', Type\Authentication::class),
            new ClassMap('authenticationFault', Type\AuthenticationFault::class),
        ]);
    }
}
