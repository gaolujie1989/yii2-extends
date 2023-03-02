<?php

namespace lujie\dpd\soap\Type;

use yii\base\BaseObject;

class HolidayType extends BaseObject
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
    public function getHolidayStart() : string
    {
        return $this->holidayStart;
    }

    /**
     * @param string $holidayStart
     * @return $this
     */
    public function setHolidayStart(string $holidayStart) : \lujie\dpd\soap\Type\HolidayType
    {
        $this->holidayStart = $holidayStart;
        return $this;
    }

    /**
     * @param string $holidayStart
     * @return HolidayType
     */
    public function withHolidayStart(string $holidayStart) : \lujie\dpd\soap\Type\HolidayType
    {
        $new = clone $this;
        $new->holidayStart = $holidayStart;

        return $new;
    }

    /**
     * @return string
     */
    public function getHolidayEnd() : string
    {
        return $this->holidayEnd;
    }

    /**
     * @param string $holidayEnd
     * @return $this
     */
    public function setHolidayEnd(string $holidayEnd) : \lujie\dpd\soap\Type\HolidayType
    {
        $this->holidayEnd = $holidayEnd;
        return $this;
    }

    /**
     * @param string $holidayEnd
     * @return HolidayType
     */
    public function withHolidayEnd(string $holidayEnd) : \lujie\dpd\soap\Type\HolidayType
    {
        $new = clone $this;
        $new->holidayEnd = $holidayEnd;

        return $new;
    }


}

