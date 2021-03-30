<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class ParcelShopDelivery implements RequestInterface
{

    /**
     * @var int
     */
    private $parcelShopId;

    /**
     * @var \dpd\Type\Notification
     */
    private $parcelShopNotification;

    /**
     * Constructor
     *
     * @var int $parcelShopId
     * @var \dpd\Type\Notification $parcelShopNotification
     */
    public function __construct($parcelShopId, $parcelShopNotification)
    {
        $this->parcelShopId = $parcelShopId;
        $this->parcelShopNotification = $parcelShopNotification;
    }

    /**
     * @return int
     */
    public function getParcelShopId()
    {
        return $this->parcelShopId;
    }

    /**
     * @param int $parcelShopId
     * @return ParcelShopDelivery
     */
    public function withParcelShopId($parcelShopId)
    {
        $new = clone $this;
        $new->parcelShopId = $parcelShopId;

        return $new;
    }

    /**
     * @return \dpd\Type\Notification
     */
    public function getParcelShopNotification()
    {
        return $this->parcelShopNotification;
    }

    /**
     * @param \dpd\Type\Notification $parcelShopNotification
     * @return ParcelShopDelivery
     */
    public function withParcelShopNotification($parcelShopNotification)
    {
        $new = clone $this;
        $new->parcelShopNotification = $parcelShopNotification;

        return $new;
    }
}
