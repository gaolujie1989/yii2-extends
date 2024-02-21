<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\ResultInterface;

class StoreOrdersResponse extends BaseObject implements ResultInterface
{
    /**
     * @var \lujie\dpd\soap\Type\StoreOrdersResponseType
     */
    private $orderResult;

    /**
     * @return \lujie\dpd\soap\Type\StoreOrdersResponseType
     */
    public function getOrderResult()
    {
        return $this->orderResult;
    }

    /**
     * @param \lujie\dpd\soap\Type\StoreOrdersResponseType $orderResult
     * @return StoreOrdersResponse
     */
    public function withOrderResult($orderResult)
    {
        $new = clone $this;
        $new->orderResult = $orderResult;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\StoreOrdersResponseType $orderResult
     * @return $this
     */
    public function setOrderResult($orderResult) : \lujie\dpd\soap\Type\StoreOrdersResponse
    {
        $this->orderResult = $orderResult;
        return $this;
    }
}

