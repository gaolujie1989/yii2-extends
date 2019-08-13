<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class StoreOrders implements RequestInterface
{

    /**
     * @var \dpd\Type\PrintOptions
     */
    private $printOptions;

    /**
     * @var \dpd\Type\ShipmentServiceData
     */
    private $order;

    /**
     * Constructor
     *
     * @var \dpd\Type\PrintOptions $printOptions
     * @var \dpd\Type\ShipmentServiceData $order
     */
    public function __construct($printOptions, $order)
    {
        $this->printOptions = $printOptions;
        $this->order = $order;
    }

    /**
     * @return \dpd\Type\PrintOptions
     */
    public function getPrintOptions()
    {
        return $this->printOptions;
    }

    /**
     * @param \dpd\Type\PrintOptions $printOptions
     * @return StoreOrders
     */
    public function withPrintOptions($printOptions)
    {
        $new = clone $this;
        $new->printOptions = $printOptions;

        return $new;
    }

    /**
     * @return \dpd\Type\ShipmentServiceData
     */
    public function getOrder()
    {
        return $this->order;
    }

    /**
     * @param \dpd\Type\ShipmentServiceData $order
     * @return StoreOrders
     */
    public function withOrder($order)
    {
        $new = clone $this;
        $new->order = $order;

        return $new;
    }


}

