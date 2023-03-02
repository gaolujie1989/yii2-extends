<?php

namespace lujie\dpd\soap;

use Phpro\SoapClient\Type\ResultInterface;
use lujie\dpd\soap\Type;
use Phpro\SoapClient\Exception\SoapException;
use Phpro\SoapClient\Type\RequestInterface;

class LoginServiceClient extends \Phpro\SoapClient\Client
{

    /**
     * @param RequestInterface|Type\GetAuth $parameters
     * @return ResultInterface|Type\GetAuthResponse
     * @throws SoapException
     */
    public function getAuth(\lujie\dpd\soap\Type\GetAuth $parameters) : \lujie\dpd\soap\Type\GetAuthResponse
    {
        return $this->call('getAuth', $parameters);
    }


}

