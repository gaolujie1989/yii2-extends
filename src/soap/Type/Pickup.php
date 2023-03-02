<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class Pickup extends BaseObject
{

    /**
     * @var int
     */
    private $tour;

    /**
     * @var int
     */
    private $quantity;

    /**
     * @var int
     */
    private $date;

    /**
     * @var string
     */
    private $fromTime1;

    /**
     * @var string
     */
    private $toTime1;

    /**
     * @var string
     */
    private $fromTime2;

    /**
     * @var string
     */
    private $toTime2;

    /**
     * @var int
     */
    private $extraPickup;

    /**
     * @var string
     */
    private $boxId;

    /**
     * @var string
     */
    private $boxTan;

    /**
     * @var \lujie\dpd\soap\Type\Address
     */
    private $collectionRequestAddress;

    /**
     * @return int
     */
    public function getTour()
    {
        return $this->tour;
    }

    /**
     * @param int $tour
     * @return $this
     */
    public function setTour(int $tour) : \lujie\dpd\soap\Type\Pickup
    {
        $this->tour = $tour;
        return $this;
    }

    /**
     * @param int $tour
     * @return Pickup
     */
    public function withTour(int $tour) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->tour = $tour;

        return $new;
    }

    /**
     * @return int
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param int $quantity
     * @return $this
     */
    public function setQuantity(int $quantity) : \lujie\dpd\soap\Type\Pickup
    {
        $this->quantity = $quantity;
        return $this;
    }

    /**
     * @param int $quantity
     * @return Pickup
     */
    public function withQuantity(int $quantity) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->quantity = $quantity;

        return $new;
    }

    /**
     * @return int
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @param int $date
     * @return $this
     */
    public function setDate(int $date) : \lujie\dpd\soap\Type\Pickup
    {
        $this->date = $date;
        return $this;
    }

    /**
     * @param int $date
     * @return Pickup
     */
    public function withDate(int $date) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->date = $date;

        return $new;
    }

    /**
     * @return string
     */
    public function getFromTime1()
    {
        return $this->fromTime1;
    }

    /**
     * @param string $fromTime1
     * @return $this
     */
    public function setFromTime1(string $fromTime1) : \lujie\dpd\soap\Type\Pickup
    {
        $this->fromTime1 = $fromTime1;
        return $this;
    }

    /**
     * @param string $fromTime1
     * @return Pickup
     */
    public function withFromTime1(string $fromTime1) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->fromTime1 = $fromTime1;

        return $new;
    }

    /**
     * @return string
     */
    public function getToTime1()
    {
        return $this->toTime1;
    }

    /**
     * @param string $toTime1
     * @return $this
     */
    public function setToTime1(string $toTime1) : \lujie\dpd\soap\Type\Pickup
    {
        $this->toTime1 = $toTime1;
        return $this;
    }

    /**
     * @param string $toTime1
     * @return Pickup
     */
    public function withToTime1(string $toTime1) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->toTime1 = $toTime1;

        return $new;
    }

    /**
     * @return string
     */
    public function getFromTime2()
    {
        return $this->fromTime2;
    }

    /**
     * @param string $fromTime2
     * @return $this
     */
    public function setFromTime2(string $fromTime2) : \lujie\dpd\soap\Type\Pickup
    {
        $this->fromTime2 = $fromTime2;
        return $this;
    }

    /**
     * @param string $fromTime2
     * @return Pickup
     */
    public function withFromTime2(string $fromTime2) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->fromTime2 = $fromTime2;

        return $new;
    }

    /**
     * @return string
     */
    public function getToTime2()
    {
        return $this->toTime2;
    }

    /**
     * @param string $toTime2
     * @return $this
     */
    public function setToTime2(string $toTime2) : \lujie\dpd\soap\Type\Pickup
    {
        $this->toTime2 = $toTime2;
        return $this;
    }

    /**
     * @param string $toTime2
     * @return Pickup
     */
    public function withToTime2(string $toTime2) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->toTime2 = $toTime2;

        return $new;
    }

    /**
     * @return int
     */
    public function getExtraPickup()
    {
        return $this->extraPickup;
    }

    /**
     * @param int $extraPickup
     * @return $this
     */
    public function setExtraPickup(int $extraPickup) : \lujie\dpd\soap\Type\Pickup
    {
        $this->extraPickup = $extraPickup;
        return $this;
    }

    /**
     * @param int $extraPickup
     * @return Pickup
     */
    public function withExtraPickup(int $extraPickup) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->extraPickup = $extraPickup;

        return $new;
    }

    /**
     * @return string
     */
    public function getBoxId()
    {
        return $this->boxId;
    }

    /**
     * @param string $boxId
     * @return $this
     */
    public function setBoxId(string $boxId) : \lujie\dpd\soap\Type\Pickup
    {
        $this->boxId = $boxId;
        return $this;
    }

    /**
     * @param string $boxId
     * @return Pickup
     */
    public function withBoxId(string $boxId) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->boxId = $boxId;

        return $new;
    }

    /**
     * @return string
     */
    public function getBoxTan()
    {
        return $this->boxTan;
    }

    /**
     * @param string $boxTan
     * @return $this
     */
    public function setBoxTan(string $boxTan) : \lujie\dpd\soap\Type\Pickup
    {
        $this->boxTan = $boxTan;
        return $this;
    }

    /**
     * @param string $boxTan
     * @return Pickup
     */
    public function withBoxTan(string $boxTan) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->boxTan = $boxTan;

        return $new;
    }

    /**
     * @return \lujie\dpd\soap\Type\Address
     */
    public function getCollectionRequestAddress()
    {
        return $this->collectionRequestAddress;
    }

    /**
     * @param \lujie\dpd\soap\Type\Address $collectionRequestAddress
     * @return $this
     */
    public function setCollectionRequestAddress($collectionRequestAddress) : \lujie\dpd\soap\Type\Pickup
    {
        $this->collectionRequestAddress = $collectionRequestAddress;
        return $this;
    }

    /**
     * @param \lujie\dpd\soap\Type\Address $collectionRequestAddress
     * @return Pickup
     */
    public function withCollectionRequestAddress(\lujie\dpd\soap\Type\Address $collectionRequestAddress) : \lujie\dpd\soap\Type\Pickup
    {
        $new = clone $this;
        $new->collectionRequestAddress = $collectionRequestAddress;

        return $new;
    }


}

