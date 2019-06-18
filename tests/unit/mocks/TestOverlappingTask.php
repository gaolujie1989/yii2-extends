<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\scheduling\tests\unit\mocks;

use lujie\scheduling\CronTask;

class TestOverlappingTask extends CronTask
{
    private $_isWithoutOverlapping = true;

    /**
     * //not release lock after execute for test mutex lock
     * @inheritdoc
     */
    public function execute(): void
    {
        $this->_isWithoutOverlapping = false;
    }

    public function isWithoutOverlapping(): bool
    {
        return $this->_isWithoutOverlapping;
    }
}
