<?php

namespace dpd;

use Phpro\SoapClient\Type\ResultInterface;
use dpd\Type;
use Phpro\SoapClient\Exception\SoapException;
use Phpro\SoapClient\Type\RequestInterface;

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

