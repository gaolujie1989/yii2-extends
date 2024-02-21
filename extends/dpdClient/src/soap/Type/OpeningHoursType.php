<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class OpeningHoursType extends BaseObject
{
    /**
     * @var string
     */
    private $weekday;

    /**
     * @var string
     */
    private $openMorning;

    /**
     * @var string
     */
    private $closeMorning;

    /**
     * @var string
     */
    private $closeAfternoon;

    /**
     * @var string
     */
    private $openAfternoon;

    /**
     * @return string
     */
    public function getWeekday()
    {
        return $this->weekday;
    }

    /**
     * @param string $weekday
     * @return OpeningHoursType
     */
    public function withWeekday($weekday)
    {
        $new = clone $this;
        $new->weekday = $weekday;

        return $new;
    }

    /**
     * @param string $weekday
     * @return $this
     */
    public function setWeekday(string $weekday) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $this->weekday = $weekday;
        return $this;
    }

    /**
     * @return string
     */
    public function getOpenMorning()
    {
        return $this->openMorning;
    }

    /**
     * @param string $openMorning
     * @return OpeningHoursType
     */
    public function withOpenMorning($openMorning)
    {
        $new = clone $this;
        $new->openMorning = $openMorning;

        return $new;
    }

    /**
     * @param string $openMorning
     * @return $this
     */
    public function setOpenMorning(string $openMorning) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $this->openMorning = $openMorning;
        return $this;
    }

    /**
     * @return string
     */
    public function getCloseMorning()
    {
        return $this->closeMorning;
    }

    /**
     * @param string $closeMorning
     * @return OpeningHoursType
     */
    public function withCloseMorning($closeMorning)
    {
        $new = clone $this;
        $new->closeMorning = $closeMorning;

        return $new;
    }

    /**
     * @param string $closeMorning
     * @return $this
     */
    public function setCloseMorning(string $closeMorning) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $this->closeMorning = $closeMorning;
        return $this;
    }

    /**
     * @return string
     */
    public function getCloseAfternoon()
    {
        return $this->closeAfternoon;
    }

    /**
     * @param string $closeAfternoon
     * @return OpeningHoursType
     */
    public function withCloseAfternoon($closeAfternoon)
    {
        $new = clone $this;
        $new->closeAfternoon = $closeAfternoon;

        return $new;
    }

    /**
     * @param string $closeAfternoon
     * @return $this
     */
    public function setCloseAfternoon(string $closeAfternoon) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $this->closeAfternoon = $closeAfternoon;
        return $this;
    }

    /**
     * @return string
     */
    public function getOpenAfternoon()
    {
        return $this->openAfternoon;
    }

    /**
     * @param string $openAfternoon
     * @return OpeningHoursType
     */
    public function withOpenAfternoon($openAfternoon)
    {
        $new = clone $this;
        $new->openAfternoon = $openAfternoon;

        return $new;
    }

    /**
     * @param string $openAfternoon
     * @return $this
     */
    public function setOpenAfternoon(string $openAfternoon) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $this->openAfternoon = $openAfternoon;
        return $this;
    }
}

