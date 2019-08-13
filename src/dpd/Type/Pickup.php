<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Pickup implements RequestInterface
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
    private $day;

    /**
     * @var int
     */
    private $fromTime1;

    /**
     * @var int
     */
    private $toTime1;

    /**
     * @var int
     */
    private $fromTime2;

    /**
     * @var int
     */
    private $toTime2;

    /**
     * @var bool
     */
    private $extraPickup;

    /**
     * @var \dpd\Type\Address
     */
    private $collectionRequestAddress;

    /**
     * Constructor
     *
     * @var int $tour
     * @var int $quantity
     * @var int $date
     * @var string $day
     * @var int $fromTime1
     * @var int $toTime1
     * @var int $fromTime2
     * @var int $toTime2
     * @var bool $extraPickup
     * @var \dpd\Type\Address $collectionRequestAddress
     */
    public function __construct($tour, $quantity, $date, $day, $fromTime1, $toTime1, $fromTime2, $toTime2, $extraPickup, $collectionRequestAddress)
    {
        $this->tour = $tour;
        $this->quantity = $quantity;
        $this->date = $date;
        $this->day = $day;
        $this->fromTime1 = $fromTime1;
        $this->toTime1 = $toTime1;
        $this->fromTime2 = $fromTime2;
        $this->toTime2 = $toTime2;
        $this->extraPickup = $extraPickup;
        $this->collectionRequestAddress = $collectionRequestAddress;
    }

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
    public function getDay()
    {
        return $this->day;
    }

    /**
     * @param string $day
     * @return Pickup
     */
    public function withDay($day)
    {
        $new = clone $this;
        $new->day = $day;

        return $new;
    }

    /**
     * @return int
     */
    public function getFromTime1()
    {
        return $this->fromTime1;
    }

    /**
     * @param int $fromTime1
     * @return Pickup
     */
    public function withFromTime1($fromTime1)
    {
        $new = clone $this;
        $new->fromTime1 = $fromTime1;

        return $new;
    }

    /**
     * @return int
     */
    public function getToTime1()
    {
        return $this->toTime1;
    }

    /**
     * @param int $toTime1
     * @return Pickup
     */
    public function withToTime1($toTime1)
    {
        $new = clone $this;
        $new->toTime1 = $toTime1;

        return $new;
    }

    /**
     * @return int
     */
    public function getFromTime2()
    {
        return $this->fromTime2;
    }

    /**
     * @param int $fromTime2
     * @return Pickup
     */
    public function withFromTime2($fromTime2)
    {
        $new = clone $this;
        $new->fromTime2 = $fromTime2;

        return $new;
    }

    /**
     * @return int
     */
    public function getToTime2()
    {
        return $this->toTime2;
    }

    /**
     * @param int $toTime2
     * @return Pickup
     */
    public function withToTime2($toTime2)
    {
        $new = clone $this;
        $new->toTime2 = $toTime2;

        return $new;
    }

    /**
     * @return bool
     */
    public function getExtraPickup()
    {
        return $this->extraPickup;
    }

    /**
     * @param bool $extraPickup
     * @return Pickup
     */
    public function withExtraPickup($extraPickup)
    {
        $new = clone $this;
        $new->extraPickup = $extraPickup;

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

