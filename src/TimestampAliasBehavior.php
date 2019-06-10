<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\alias\behaviors;


/**
 * Class TimestampAliasBehavior
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TimestampAliasBehavior extends AliasPropertyBehavior
{
    public $format = 'Y-m-d H:i:s';

    public $timezone = null;

    public $timestampSuffix = '_at';

    public $datetimeSuffix = '_time';

    /**
     * @param $name
     * @return int|mixed|string
     * @throws \Exception
     * @inheritdoc
     */
    public function getAliasProperty($name)
    {
        $value = parent::getAliasProperty($name);
        if (strpos($name, $this->timestampSuffix) === 0) {
            return $this->getTimestamp($value);
        }
        if (strpos($name, $this->datetimeSuffix) === 0) {
            return $this->getDatetime($value);
        }
        return $value;
    }

    /**
     * @param $name
     * @param $value
     * @throws \Exception
     * @inheritdoc
     */
    public function setAliasProperty($name, $value): void
    {
        if (strpos($name, $this->timestampSuffix) === 0) {
            $value = $this->getTimestamp($value);
        }
        if (strpos($name, $this->datetimeSuffix) === 0) {
            $value = $this->getDatetime($value);
        }
        parent::setAliasProperty($name, $value);
    }


    /**
     * @param $time
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    public function getDatetime($time): string
    {
        $dateTime = new \DateTime(date('Y-m-d H:i:s', $time));
        if ($this->timezone) {
            $dateTime->setTimezone(new \DateTimeZone($this->timezone));
        }
        return $dateTime->format($this->format);
    }

    /**
     * @param $date
     * @return int
     * @throws \Exception
     * @inheritdoc
     */
    public function getTimestamp($date): int
    {
        $timezone = $this->timezone ? new \DateTimeZone($this->timezone) : null;
        $dateTime = new \DateTime($date, $timezone);
        return $dateTime->getTimestamp();
    }
}
