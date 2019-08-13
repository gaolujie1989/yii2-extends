<?php

namespace dpd;

use Phpro\SoapClient\Type\RequestInterface;
use dpd\Type;
use Phpro\SoapClient\Type\ResultInterface;
use Phpro\SoapClient\Exception\SoapException;

class LoginServiceClient extends \Phpro\SoapClient\Client
{

    /**
     * @param RequestInterface|Type\GetAuth $parameters
     * @return ResultInterface|Type\GetAuthResponse
     * @throws SoapException
     */
    public function getAuth(\dpd\Type\GetAuth $parameters) : \dpd\Type\GetAuthResponse
    {
        return $this->call('getAuth', $parameters);
    }


}

