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
        if (substr($name, -strlen($this->timestampSuffix)) === $this->timestampSuffix) {
            return $this->getTimestamp($value);
        }
        if (substr($name, -strlen($this->datetimeSuffix)) === $this->datetimeSuffix) {
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
        $property = $this->aliasProperties[$name];
        if (substr($property, -strlen($this->timestampSuffix)) === $this->timestampSuffix) {
            $value = $this->getTimestamp($value);
        }
        if (substr($property, -strlen($this->datetimeSuffix)) === $this->datetimeSuffix) {
            $value = $this->getDatetime($value);
        }
        $this->owner->$property = $value;
    }


    /**
     * @param $time
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    public function getDatetime($time): string
    {
        if (!is_numeric($time)) {
            return $time;
        }
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
        if (is_int($date)) {
            return $date;
        }
        $timezone = $this->timezone ? new \DateTimeZone($this->timezone) : null;
        $dateTime = new \DateTime($date, $timezone);
        return $dateTime->getTimestamp();
    }
}
