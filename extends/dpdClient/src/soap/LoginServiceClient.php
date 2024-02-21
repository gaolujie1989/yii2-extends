<?php

namespace lujie\dpd\soap;

use Phpro\SoapClient\Caller\Caller;
use Phpro\SoapClient\Type\ResultInterface;
use lujie\dpd\soap\Type;
use Phpro\SoapClient\Exception\SoapException;
use Phpro\SoapClient\Type\RequestInterface;

class LoginServiceClient
{
    /**
     * @var Caller
     */
    private $caller;

    public function __construct(\Phpro\SoapClient\Caller\Caller $caller)
    {
        $this->caller = $caller;
    }

    /**
     * @param RequestInterface|Type\GetAuth $parameters
     * @return ResultInterface|Type\GetAuthResponse
     * @throws SoapException
     */
    public function getAuth(\lujie\dpd\soap\Type\GetAuth $parameters) : \lujie\dpd\soap\Type\GetAuthResponse
    {
        return ($this->caller)('getAuth', $parameters);
    }
}

