<?php

namespace dpd\Type;

class Delivery
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
     * @var string
     */
    private $timeFrom;

    /**
     * @var string
     */
    private $timeTo;

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
     * @return string
     */
    public function getTimeFrom()
    {
        return $this->timeFrom;
    }

    /**
     * @param string $timeFrom
     * @return Delivery
     */
    public function withTimeFrom($timeFrom)
    {
        $new = clone $this;
        $new->timeFrom = $timeFrom;

        return $new;
    }

    /**
     * @return string
     */
    public function getTimeTo()
    {
        return $this->timeTo;
    }

    /**
     * @param string $timeTo
     * @return Delivery
     */
    public function withTimeTo($timeTo)
    {
        $new = clone $this;
        $new->timeTo = $timeTo;

        return $new;
    }


}

