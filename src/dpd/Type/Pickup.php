<?php

namespace dpd\Type;

class Pickup
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
     * @var \dpd\Type\Address
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
     * @return Pickup
     */
    public function withTour($tour)
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
     * @return Pickup
     */
    public function withQuantity($quantity)
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
     * @return Pickup
     */
    public function withDate($date)
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
     * @return Pickup
     */
    public function withFromTime1($fromTime1)
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
     * @return Pickup
     */
    public function withToTime1($toTime1)
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
     * @return Pickup
     */
    public function withFromTime2($fromTime2)
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
     * @return Pickup
     */
    public function withToTime2($toTime2)
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
     * @return Pickup
     */
    public function withExtraPickup($extraPickup)
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
     * @return Pickup
     */
    public function withBoxId($boxId)
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
     * @return Pickup
     */
    public function withBoxTan($boxTan)
    {
        $new = clone $this;
        $new->boxTan = $boxTan;

        return $new;
    }

    /**
     * @return \dpd\Type\Address
     */
    public function getCollectionRequestAddress()
    {
        return $this->collectionRequestAddress;
    }

    /**
     * @param \dpd\Type\Address $collectionRequestAddress
     * @return Pickup
     */
    public function withCollectionRequestAddress($collectionRequestAddress)
    {
        $new = clone $this;
        $new->collectionRequestAddress = $collectionRequestAddress;

        return $new;
    }


}

