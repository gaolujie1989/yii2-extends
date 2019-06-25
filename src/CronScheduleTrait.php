<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;

use Cron\CronExpression;
use Yii;

/**
 * Class CronScheduleTrait
 * @package lujie\scheduling
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait CronScheduleTrait
{
    /**
     * @var string
     */
    public $expression = '0 0 * * *';

    /**
     * @var string
     */
    public $timezone;

    /**
     * @return bool
     * @throws \Exception
     * @inheritdoc
     */
    public function isDue(): bool
    {
        $dateTime = new \DateTime();
        if ($this->getTimezone()) {
            $dateTime->setTimezone(new \DateTimeZone($this->getTimezone()));
        }
        return CronExpression::factory($this->expression)->isDue($dateTime);
    }

    /**
     * @return \DateTime
     * @throws \Exception
     * @inheritdoc
     */
    public function getNextRunTime(): \DateTime
    {
        $dateTime = new \DateTime();
        if ($this->getTimezone()) {
            $dateTime->setTimezone(new \DateTimeZone($this->getTimezone()));
        }
        return CronExpression::factory($this->expression)->getNextRunDate($dateTime);
    }

    /**
     * @return string
     * @inheritdoc
     */
    public function getTimezone(): string
    {
        return $this->timezone ?? Yii::$app->timeZone;
    }
}
