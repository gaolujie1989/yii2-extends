<?php

namespace dpd;

use dpd\Type;
use Phpro\SoapClient\Exception\SoapException;
use Phpro\SoapClient\Type\RequestInterface;
use Phpro\SoapClient\Type\ResultInterface;

class LoginServiceClient extends \Phpro\SoapClient\Client
{

    /**
     * @param RequestInterface|Type\GetAuth $parameters
     * @return ResultInterface|Type\GetAuthResponse
     * @throws SoapException
     */
    public function getAuth(\dpd\Type\GetAuth $parameters): \dpd\Type\GetAuthResponse
    {
        return $this->call('getAuth', $parameters);
    }
}
