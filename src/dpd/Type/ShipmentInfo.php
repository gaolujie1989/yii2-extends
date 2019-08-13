<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ShipmentInfo implements RequestInterface
{

    /**
     * @var \dpd\Type\ContentItem
     */
    private $receiver;

    /**
     * @var \dpd\Type\ContentItem
     */
    private $predictInformation;

    /**
     * @var \dpd\Type\ContentItem
     */
    private $serviceDescription;

    /**
     * @var \dpd\Type\ContentItem
     */
    private $additionalServiceElements;

    /**
     * @var \dpd\Type\TrackingProperty
     */
    private $trackingProperty;

    /**
     * Constructor
     *
     * @var \dpd\Type\ContentItem $receiver
     * @var \dpd\Type\ContentItem $predictInformation
     * @var \dpd\Type\ContentItem $serviceDescription
     * @var \dpd\Type\ContentItem $additionalServiceElements
     * @var \dpd\Type\TrackingProperty $trackingProperty
     */
    public function __construct($receiver, $predictInformation, $serviceDescription, $additionalServiceElements, $trackingProperty)
    {
        $this->receiver = $receiver;
        $this->predictInformation = $predictInformation;
        $this->serviceDescription = $serviceDescription;
        $this->additionalServiceElements = $additionalServiceElements;
        $this->trackingProperty = $trackingProperty;
    }

    /**
     * @return \dpd\Type\ContentItem
     */
    public function getReceiver()
    {
        return $this->receiver;
    }

    /**
     * @param \dpd\Type\ContentItem $receiver
     * @return ShipmentInfo
     */
    public function withReceiver($receiver)
    {
        $new = clone $this;
        $new->receiver = $receiver;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentItem
     */
    public function getPredictInformation()
    {
        return $this->predictInformation;
    }

    /**
     * @param \dpd\Type\ContentItem $predictInformation
     * @return ShipmentInfo
     */
    public function withPredictInformation($predictInformation)
    {
        $new = clone $this;
        $new->predictInformation = $predictInformation;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentItem
     */
    public function getServiceDescription()
    {
        return $this->serviceDescription;
    }

    /**
     * @param \dpd\Type\ContentItem $serviceDescription
     * @return ShipmentInfo
     */
    public function withServiceDescription($serviceDescription)
    {
        $new = clone $this;
        $new->serviceDescription = $serviceDescription;

        return $new;
    }

    /**
     * @return \dpd\Type\ContentItem
     */
    public function getAdditionalServiceElements()
    {
        return $this->additionalServiceElements;
    }

    /**
     * @param \dpd\Type\ContentItem $additionalServiceElements
     * @return ShipmentInfo
     */
    public function withAdditionalServiceElements($additionalServiceElements)
    {
        $new = clone $this;
        $new->additionalServiceElements = $additionalServiceElements;

        return $new;
    }

    /**
     * @return \dpd\Type\TrackingProperty
     */
    public function getTrackingProperty()
    {
        return $this->trackingProperty;
    }

    /**
     * @param \dpd\Type\TrackingProperty $trackingProperty
     * @return ShipmentInfo
     */
    public function withTrackingProperty($trackingProperty)
    {
        $new = clone $this;
        $new->trackingProperty = $trackingProperty;

        return $new;
    }


}

