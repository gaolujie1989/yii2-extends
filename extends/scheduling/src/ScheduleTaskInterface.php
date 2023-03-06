<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\scheduling;

use lujie\executing\ExecutableInterface;
use lujie\executing\LockableInterface;
use lujie\executing\QueueableInterface;

/**
 * Interface ScheduleTaskInterface
 * @package lujie\scheduling
 */
interface ScheduleTaskInterface extends ScheduleInterface, ExecutableInterface, QueueableInterface, LockableInterface
{
}
