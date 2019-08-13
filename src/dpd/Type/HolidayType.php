<?php

namespace dpd\Type;

use Phpro\SoapClient\Type\RequestInterface;

class HolidayType implements RequestInterface
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
     * Constructor
     *
     * @var string $holidayStart
     * @var string $holidayEnd
     */
    public function __construct($holidayStart, $holidayEnd)
    {
        $this->holidayStart = $holidayStart;
        $this->holidayEnd = $holidayEnd;
    }

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

