<?php

namespace dpd\Type;

class OpeningHoursType
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


}

