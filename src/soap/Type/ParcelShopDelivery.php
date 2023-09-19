<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class ParcelShopDelivery extends BaseObject
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
     * @var \lujie\dpd\soap\Type\Notification
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
     * @param int $parcelShopId
     * @return $this
     */
    public function setParcelShopId(int $parcelShopId) : \lujie\dpd\soap\Type\ParcelShopDelivery
    {
        $this->parcelShopId = $parcelShopId;
        return $this;
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
     * @param string $parcelShopPudoId
     * @return $this
     */
    public function setParcelShopPudoId(string $parcelShopPudoId) : \lujie\dpd\soap\Type\ParcelShopDelivery
    {
        $this->parcelShopPudoId = $parcelShopPudoId;
        return $this;
    }

    /**
     * @return \lujie\dpd\soap\Type\Notification
     */
    public function getParcelShopNotification()
    {
        return $this->parcelShopNotification;
    }

    /**
     * @param \lujie\dpd\soap\Type\Notification $parcelShopNotification
     * @return ParcelShopDelivery
     */
    public function withParcelShopNotification($parcelShopNotification)
    {
        $new = clone $this;
        $new->parcelShopNotification = $parcelShopNotification;

        return $new;
    }

    /**
     * @param \lujie\dpd\soap\Type\Notification $parcelShopNotification
     * @return $this
     */
    public function setParcelShopNotification($parcelShopNotification) : \lujie\dpd\soap\Type\ParcelShopDelivery
    {
        $this->parcelShopNotification = $parcelShopNotification;
        return $this;
    }
}

