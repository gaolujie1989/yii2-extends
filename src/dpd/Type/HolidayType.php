<?php

namespace dpd\Type;

class HolidayType
{

    /**
     * @var string
     */
    private $holidayStart;

    /**
     * @var string
     */
    private $holidayEnd;

    /**
     * @return string
     */
    public function getHolidayStart()
    {
        return $this->holidayStart;
    }

    /**
     * @param string $holidayStart
     * @return HolidayType
     */
    public function withHolidayStart($holidayStart)
    {
        $new = clone $this;
        $new->holidayStart = $holidayStart;

        return $new;
    }

    /**
     * @return string
     */
    public function getHolidayEnd()
    {
        return $this->holidayEnd;
    }

    /**
     * @param string $holidayEnd
     * @return HolidayType
     */
    public function withHolidayEnd($holidayEnd)
    {
        $new = clone $this;
        $new->holidayEnd = $holidayEnd;

        return $new;
    }


}

