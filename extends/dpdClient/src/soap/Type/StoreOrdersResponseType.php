<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class StoreOrdersResponseType extends BaseObject
{
    /**
     * @var \lujie\dpd\soap\Type\OutputType
     */
    private $output;

    /**
     * @var \lujie\dpd\soap\Type\ShipmentResponse
     */
    private $shipmentResponses;

    /**
     * @return \lujie\dpd\soap\Type\OutputType
     */
    public function getOutput()
    {
        return $this->output;
    }

    /**
     * @param \lujie\dpd\soap\Type\OutputType $output
     * @return StoreOrdersResponseType
     */
    public function withOutput($output)
    {
        $new = clone $this;
        $new->output = $output;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\OutputType $output
     * @return $this
     */
    public function setOutput($output) : \lujie\dpd\soap\Type\StoreOrdersResponseType
    {
        $this->output = $output;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\ShipmentResponse
     */
    public function getShipmentResponses()
    {
        return $this->shipmentResponses;
    }

    /**
     * @param \lujie\dpd\soap\Type\ShipmentResponse $shipmentResponses
     * @return StoreOrdersResponseType
     */
    public function withShipmentResponses($shipmentResponses)
    {
        $new = clone $this;
        $new->shipmentResponses = $shipmentResponses;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\ShipmentResponse $shipmentResponses
     * @return $this
     */
    public function setShipmentResponses($shipmentResponses) : \lujie\dpd\soap\Type\StoreOrdersResponseType
    {
        $this->shipmentResponses = $shipmentResponses;
        return $this;
    }
}

