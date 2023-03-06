<?php

namespace lujie\dpd\soap;

use Phpro\SoapClient\Type\ResultInterface;
use lujie\dpd\soap\Type;
use Phpro\SoapClient\Exception\SoapException;
use Phpro\SoapClient\Type\RequestInterface;

class ShipmentServiceClient extends \Phpro\SoapClient\Client
{

    /**
     * @param RequestInterface|Type\StoreOrders $parameters
     * @return ResultInterface|Type\StoreOrdersResponse
     * @throws SoapException
     */
    public function storeOrders(\lujie\dpd\soap\Type\StoreOrders $parameters) : \lujie\dpd\soap\Type\StoreOrdersResponse
    {
        return $this->call('storeOrders', $parameters);
    }


}

