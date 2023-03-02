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
    public function getWeekday() : string
    {
        return $this->weekday;
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
     * @param string $weekday
     * @return OpeningHoursType
     */
    public function withWeekday(string $weekday) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $new = clone $this;
        $new->weekday = $weekday;

        return $new;
    }

    /**
     * @return string
     */
    public function getOpenMorning() : string
    {
        return $this->openMorning;
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
     * @param string $openMorning
     * @return OpeningHoursType
     */
    public function withOpenMorning(string $openMorning) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $new = clone $this;
        $new->openMorning = $openMorning;

        return $new;
    }

    /**
     * @return string
     */
    public function getCloseMorning() : string
    {
        return $this->closeMorning;
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
     * @param string $closeMorning
     * @return OpeningHoursType
     */
    public function withCloseMorning(string $closeMorning) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $new = clone $this;
        $new->closeMorning = $closeMorning;

        return $new;
    }

    /**
     * @return string
     */
    public function getCloseAfternoon() : string
    {
        return $this->closeAfternoon;
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
     * @param string $closeAfternoon
     * @return OpeningHoursType
     */
    public function withCloseAfternoon(string $closeAfternoon) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $new = clone $this;
        $new->closeAfternoon = $closeAfternoon;

        return $new;
    }

    /**
     * @return string
     */
    public function getOpenAfternoon() : string
    {
        return $this->openAfternoon;
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

    /**
     * @param string $openAfternoon
     * @return OpeningHoursType
     */
    public function withOpenAfternoon(string $openAfternoon) : \lujie\dpd\soap\Type\OpeningHoursType
    {
        $new = clone $this;
        $new->openAfternoon = $openAfternoon;

        return $new;
    }


}

