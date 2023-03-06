<?php
/**
 * @copyright Copyright (c) 2017
 */

namespace lujie\alias\behaviors;

use Yii;
use yii\helpers\StringHelper;

/**
 * Class TimestampAliasBehavior
 * @package lujie\core\behaviors
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class TimestampAliasBehavior extends AliasPropertyBehavior
{
    /**
     * @var string
     */
    public $format = 'c';

    /**
     * @var string|null
     */
    public $timezone = null;

    /**
     * @var string
     */
    public $timestampSuffix = '_at';

    /**
     * @var string
     * @deprecated
     */
    public $datetimeSuffix = '_time';

    /**
     * @var string
     */
    public $message = 'The format of {attribute} is invalid.';

    /**
     * @param string $name
     * @return int|mixed|string
     * @throws \Exception
     * @inheritdoc
     */
    public function getAliasProperty(string $name)
    {
        $value = parent::getAliasProperty($name);
        if (StringHelper::endsWith($name, $this->timestampSuffix)) {
            return $this->getTimestamp($value);
        } else {
            return $this->getDatetime($value);
        }
    }

    /**
     * @param string $name
     * @param mixed $value
     * @throws \Exception
     * @inheritdoc
     */
    public function setAliasProperty(string $name, $value): void
    {
        try {
            $property = $this->aliasProperties[$name];
            if (StringHelper::endsWith($property, $this->timestampSuffix)) {
                $value = $this->getTimestamp($value);
            } else {
                $value = $this->getDatetime($value);
            }
        } catch (\Exception $exception) {
            Yii::info($exception->getMessage(), __METHOD__);
        }
        parent::setAliasProperty($name, $value);
    }


    /**
     * @param string|int $time
     * @return string
     * @throws \Exception
     * @inheritdoc
     */
    public function getDatetime($time): string
    {
        if (empty($time)) {
            return '';
        }
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
     * @param string|int $date
     * @return int
     * @throws \Exception
     * @inheritdoc
     */
    public function getTimestamp($date): int
    {
        if (empty($date)) {
            return 0;
        }
        if (is_int($date)) {
            return $date;
        }
        $timezone = $this->timezone ? new \DateTimeZone($this->timezone) : null;
        $dateTime = new \DateTime($date, $timezone);
        return $dateTime->getTimestamp();
    }
}
