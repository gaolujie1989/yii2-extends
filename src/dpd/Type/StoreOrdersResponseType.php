<?php

namespace dpd\Type;

class StoreOrdersResponseType
{

    /**
     * @var \dpd\Type\OutputType
     */
    private $output;

    /**
     * @var \dpd\Type\ShipmentResponse
     */
    private $shipmentResponses;

    /**
     * @return \dpd\Type\OutputType
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \dpd\Type\OutputType $output
     * @return StoreOrdersResponseType
     */
    public function withOutput($output)
    {
        $new = clone $this;
        $new->output = $output;

        return $new;
    }

    /**
     * @return \dpd\Type\ShipmentResponse
     */
    public function getShipmentResponses()
    {
        return $this->shipmentResponses;
    }

    /**
     * @param \dpd\Type\ShipmentResponse $shipmentResponses
     * @return StoreOrdersResponseType
     */
    public function withShipmentResponses($shipmentResponses)
    {
        $new = clone $this;
        $new->shipmentResponses = $shipmentResponses;

        return $new;
    }


}

