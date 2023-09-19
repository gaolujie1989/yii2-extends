<?php

namespace lujie\dpd\soap;

use Phpro\SoapClient\Caller\Caller;
use Phpro\SoapClient\Type\ResultInterface;
use lujie\dpd\soap\Type;
use Phpro\SoapClient\Exception\SoapException;
use Phpro\SoapClient\Type\RequestInterface;

class ShipmentServiceClient
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
     * @param RequestInterface|Type\StoreOrders $parameters
     * @return ResultInterface|Type\StoreOrdersResponse
     * @throws SoapException
     */
    public function storeOrders(\lujie\dpd\soap\Type\StoreOrders $parameters) : \lujie\dpd\soap\Type\StoreOrdersResponse
    {
        return ($this->caller)('storeOrders', $parameters);
    }
}

