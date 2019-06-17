<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;


interface TaskInterface
{
    /**
     * @return string
     * @inheritdoc
     */
    public function getTaskCode(): string;

    /**
     * @return string
     * @inheritdoc
     */
    public function getTaskDescription(): string;

    /**
     * @return bool
     * @inheritdoc
     */
    public function isDue(): bool;

    /**
     * @return string
     * @inheritdoc
     */
    public function getNextRunTime(): string ;

    /**
     * @return string
     * @inheritdoc
     */
    public function getExpression(): string;

    /**
     * @return \DateTimeZone|string
     * @inheritdoc
     */
    public function getTimezone(): string;

    /**
     * @return mixed
     * @inheritdoc
     */
    public function execute();
}
