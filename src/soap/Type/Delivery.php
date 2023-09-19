<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class Delivery extends BaseObject
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
     * @param string $day
     * @return $this
     */
    public function setDay(string $day) : \lujie\dpd\soap\Type\Delivery
    {
        $this->day = $day;
        return $this;
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
     * @param int $dateFrom
     * @return $this
     */
    public function setDateFrom(int $dateFrom) : \lujie\dpd\soap\Type\Delivery
    {
        $this->dateFrom = $dateFrom;
        return $this;
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
     * @param int $dateTo
     * @return $this
     */
    public function setDateTo(int $dateTo) : \lujie\dpd\soap\Type\Delivery
    {
        $this->dateTo = $dateTo;
        return $this;
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
     * @param string $timeFrom
     * @return $this
     */
    public function setTimeFrom(string $timeFrom) : \lujie\dpd\soap\Type\Delivery
    {
        $this->timeFrom = $timeFrom;
        return $this;
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

    /**
     * @param string $timeTo
     * @return $this
     */
    public function setTimeTo(string $timeTo) : \lujie\dpd\soap\Type\Delivery
    {
        $this->timeTo = $timeTo;
        return $this;
    }
}

