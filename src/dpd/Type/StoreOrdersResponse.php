<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\ResultInterface;

class StoreOrdersResponse implements ResultInterface
{

    /**
     * @var \dpd\Type\StoreOrdersResponseType
     */
    private $orderResult;

    /**
     * @return \dpd\Type\StoreOrdersResponseType
     */
    public function getOrderResult()
    {
        return $this->orderResult;
    }

    /**
     * @param \dpd\Type\StoreOrdersResponseType $orderResult
     * @return StoreOrdersResponse
     */
    public function withOrderResult($orderResult)
    {
        $new = clone $this;
        $new->orderResult = $orderResult;

        return $new;
    }


}

