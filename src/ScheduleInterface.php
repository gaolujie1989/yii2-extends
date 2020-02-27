<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling;


interface ScheduleInterface
{
    /**
     * @return bool
     * @inheritdoc
     */
    public function isDue(): bool;

    /**
     * @return \DateTime
     * @inheritdoc
     */
    public function getNextRunTime(): \DateTime;

    /**
     * @return string
     * @inheritdoc
     */
    public function getTimezone(): string;
}
