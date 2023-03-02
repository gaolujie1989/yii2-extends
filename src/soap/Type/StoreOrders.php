<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;
use Phpro\SoapClient\Type\RequestInterface;

class StoreOrders extends BaseObject implements RequestInterface
{

    /**
     * @var \lujie\dpd\soap\Type\PrintOptions
     */
    private $printOptions;

    /**
     * @var \lujie\dpd\soap\Type\ShipmentServiceData
     */
    private $order;

    /**
     * @return \lujie\dpd\soap\Type\PrintOptions
     */
    public function getPrintOptions()
    {
        return $this->printOptions;
    }

    /**
     * @param \lujie\dpd\soap\Type\PrintOptions $printOptions
     * @return $this
     */
    public function setPrintOptions($printOptions) : \lujie\dpd\soap\Type\StoreOrders
    {
        $this->printOptions = $printOptions;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\PrintOptions $printOptions
     * @return StoreOrders
     */
    public function withPrintOptions(\lujie\dpd\soap\Type\PrintOptions $printOptions) : \lujie\dpd\soap\Type\StoreOrders
    {
        $new = clone $this;
        $new->printOptions = $printOptions;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\ShipmentServiceData
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param \lujie\dpd\soap\Type\ShipmentServiceData $order
     * @return $this
     */
    public function setOrder($order) : \lujie\dpd\soap\Type\StoreOrders
    {
        $this->order = $order;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\ShipmentServiceData $order
     * @return StoreOrders
     */
    public function withOrder(\lujie\dpd\soap\Type\ShipmentServiceData $order) : \lujie\dpd\soap\Type\StoreOrders
    {
        $new = clone $this;
        $new->order = $order;

        return $new;
    }


}

