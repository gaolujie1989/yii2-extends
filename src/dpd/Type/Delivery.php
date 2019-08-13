<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class Delivery implements RequestInterface
{

    /**
     * @var string
     */
    private $day;

    /**
     * @var int
     */
    private $dateFrom;

    /**
     * @var int
     */
    private $dateTo;

    /**
     * @var int
     */
    private $timeFrom;

    /**
     * @var int
     */
    private $timeTo;

    /**
     * Constructor
     *
     * @var string $day
     * @var int $dateFrom
     * @var int $dateTo
     * @var int $timeFrom
     * @var int $timeTo
     */
    public function __construct($day, $dateFrom, $dateTo, $timeFrom, $timeTo)
    {
        $this->day = $day;
        $this->dateFrom = $dateFrom;
        $this->dateTo = $dateTo;
        $this->timeFrom = $timeFrom;
        $this->timeTo = $timeTo;
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
     * @return Delivery
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
    public function getDateFrom()
    {
        return $this->dateFrom;
    }

    /**
     * @param int $dateFrom
     * @return Delivery
     */
    public function withDateFrom($dateFrom)
    {
        $new = clone $this;
        $new->dateFrom = $dateFrom;

        return $new;
    }

    /**
     * @return int
     */
    public function getDateTo()
    {
        return $this->dateTo;
    }

    /**
     * @param int $dateTo
     * @return Delivery
     */
    public function withDateTo($dateTo)
    {
        $new = clone $this;
        $new->dateTo = $dateTo;

        return $new;
    }

    /**
     * @return int
     */
    public function getTimeFrom()
    {
        return $this->timeFrom;
    }

    /**
     * @param int $timeFrom
     * @return Delivery
     */
    public function withTimeFrom($timeFrom)
    {
        $new = clone $this;
        $new->timeFrom = $timeFrom;

        return $new;
    }

    /**
     * @return int
     */
    public function getTimeTo()
    {
        return $this->timeTo;
    }

    /**
     * @param int $timeTo
     * @return Delivery
     */
    public function withTimeTo($timeTo)
    {
        $new = clone $this;
        $new->timeTo = $timeTo;

        return $new;
    }


}

