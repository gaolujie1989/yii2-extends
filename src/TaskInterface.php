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
    public function getTaskCode();

    /**
     * @return string
     * @inheritdoc
     */
    public function getTaskDescription();

    /**
     * @return bool
     * @inheritdoc
     */
    public function isDue();

    /**
     * @return mixed
     * @inheritdoc
     */
    public function getNextRunTime();

    /**
     * @return string
     * @inheritdoc
     */
    public function getExpression();

    /**
     * @return \DateTimeZone|string
     * @inheritdoc
     */
    public function getTimezone();

    /**
     * @return mixed
     * @inheritdoc
     */
    public function execute();
}