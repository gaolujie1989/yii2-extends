<?php

namespace dpd\Type;

class ParcelShopDelivery
{

    /**
     * @var int
     */
    private $parcelShopId;

    /**
     * @var string
     */
    private $parcelShopPudoId;

    /**
     * @var \dpd\Type\Notification
     */
    private $parcelShopNotification;

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
     * @return string
     */
    public function getParcelShopPudoId()
    {
        return $this->parcelShopPudoId;
    }

    /**
     * @param string $parcelShopPudoId
     * @return ParcelShopDelivery
     */
    public function withParcelShopPudoId($parcelShopPudoId)
    {
        $new = clone $this;
        $new->parcelShopPudoId = $parcelShopPudoId;

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

