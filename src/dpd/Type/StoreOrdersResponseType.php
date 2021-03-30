<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class StoreOrdersResponseType implements RequestInterface
{

    /**
     * @var string
     */
    private $parcellabelsPDF;

    /**
     * @var \dpd\Type\ShipmentResponse[]
     */
    private $shipmentResponses;

    /**
     * Constructor
     *
     * @var string $parcellabelsPDF
     * @var \dpd\Type\ShipmentResponse $shipmentResponses
     */
    public function __construct($parcellabelsPDF, $shipmentResponses)
    {
        $this->parcellabelsPDF = $parcellabelsPDF;
        $this->shipmentResponses = $shipmentResponses;
    }

    /**
     * @return string
     */
    public function getParcellabelsPDF()
    {
        return $this->parcellabelsPDF;
    }

    /**
     * @param string $parcellabelsPDF
     * @return StoreOrdersResponseType
     */
    public function withParcellabelsPDF($parcellabelsPDF)
    {
        $new = clone $this;
        $new->parcellabelsPDF = $parcellabelsPDF;

        return $new;
    }

    /**
     * @return \dpd\Type\ShipmentResponse[]
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
