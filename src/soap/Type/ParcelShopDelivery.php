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
    public function getParcelShopId() : int
    {
        return $this->parcelShopId;
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
     * @param int $parcelShopId
     * @return ParcelShopDelivery
     */
    public function withParcelShopId(int $parcelShopId) : \lujie\dpd\soap\Type\ParcelShopDelivery
    {
        $new = clone $this;
        $new->parcelShopId = $parcelShopId;

        return $new;
    }

    /**
     * @return string
     */
    public function getParcelShopPudoId() : string
    {
        return $this->parcelShopPudoId;
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
     * @param string $parcelShopPudoId
     * @return ParcelShopDelivery
     */
    public function withParcelShopPudoId(string $parcelShopPudoId) : \lujie\dpd\soap\Type\ParcelShopDelivery
    {
        $new = clone $this;
        $new->parcelShopPudoId = $parcelShopPudoId;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\Notification
     */
    public function getParcelShopNotification() : \lujie\dpd\soap\Type\Notification
    {
        return $this->parcelShopNotification;
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

    /**
     * @param \lujie\dpd\soap\Type\Notification $parcelShopNotification
     * @return ParcelShopDelivery
     */
    public function withParcelShopNotification(\lujie\dpd\soap\Type\Notification $parcelShopNotification) : \lujie\dpd\soap\Type\ParcelShopDelivery
    {
        $new = clone $this;
        $new->parcelShopNotification = $parcelShopNotification;

        return $new;
    }


}

