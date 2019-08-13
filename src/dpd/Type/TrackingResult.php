<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class TrackingResult implements RequestInterface
{

    /**
     * @var \dpd\Type\ShipmentInfo
     */
    private $shipmentInfo;

    /**
     * @var \dpd\Type\StatusInfo
     */
    private $statusInfo;

    /**
     * @var \dpd\Type\ContentItem
     */
    private $contactInfo;

    /**
     * Constructor
     *
     * @var \dpd\Type\ShipmentInfo $shipmentInfo
     * @var \dpd\Type\StatusInfo $statusInfo
     * @var \dpd\Type\ContentItem $contactInfo
     */
    public function __construct($shipmentInfo, $statusInfo, $contactInfo)
    {
        $this->shipmentInfo = $shipmentInfo;
        $this->statusInfo = $statusInfo;
        $this->contactInfo = $contactInfo;
    }

    /**
     * @return \dpd\Type\ShipmentInfo
     */
    public function getShipmentInfo()
    {
        return $this->shipmentInfo;
    }

    /**
     * @param \dpd\Type\ShipmentInfo $shipmentInfo
     * @return TrackingResult
     */
    public function withShipmentInfo($shipmentInfo)
    {
        $new = clone $this;
        $new->shipmentInfo = $shipmentInfo;

        return $new;
    }

    /**
     * @return \dpd\Type\StatusInfo
     */
    public function getStatusInfo()
    {
        return $this->statusInfo;
    }

    /**
     * @param \dpd\Type\StatusInfo $statusInfo
     * @return TrackingResult
     */
    public function withStatusInfo($statusInfo)
    {
        $new = clone $this;
        $new->statusInfo = $statusInfo;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentItem
     */
    public function getContactInfo()
    {
        return $this->contactInfo;
    }

    /**
     * @param \dpd\Type\ContentItem $contactInfo
     * @return TrackingResult
     */
    public function withContactInfo($contactInfo)
    {
        $new = clone $this;
        $new->contactInfo = $contactInfo;

        return $new;
    }


}

