<?php

namespace dpd;

use Phpro\SoapClient\Type\RequestInterface;
use dpd\Type;
use Phpro\SoapClient\Type\ResultInterface;
use Phpro\SoapClient\Exception\SoapException;

class ShipmentServiceClient extends \Phpro\SoapClient\Client
{

    /**
     * @param RequestInterface|Type\StoreOrders $parameters
     * @return ResultInterface|Type\StoreOrdersResponse
     * @throws SoapException
     */
    public function storeOrders(\dpd\Type\StoreOrders $parameters) : \dpd\Type\StoreOrdersResponse
    {
        return $this->call('storeOrders', $parameters);
    }


}

