<?php
/**
 * @copyright Copyright (c) 2023
 */

namespace lujie\extend\simpleFork;

use Jenner\SimpleFork\AbstractPool;
use Jenner\SimpleFork\Process;

/**
 * Class ProgressFixedPool
 * @package lujie\extend\simpleFork
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class DynamicPool extends AbstractPool
{
    /**
     * @var int max process count
     */
    public $max;

    /**
     * @var ?PoolLoopInterface
     */
    public $loop;

    /**
     * @param int $max
     */
    public function __construct(int $max = 4)
    {
        $this->max = $max;
    }

    /**
     * @param Process $process
     * @inheritdoc
     */
    public function execute(Process $process): void
    {
        if ($this->aliveCount() < $this->max && !$process->isStarted()) {
            $process->start();
        }
        $this->processes[] = $process;
    }

    /**
     * wait for all process done
     *
     * @param bool $block block the master process
     * to keep the sub process count all the time
     * @param int $sleep check time interval
     */
    public function wait($block = false, $sleep = 100): void
    {
        do {
            if ($this->isFinished()) {
                return;
            }
            parent::wait(false);
            if ($this->loop instanceof PoolLoopInterface) {
                $this->loop->loop($this);
            }
            if ($this->aliveCount() < $this->max) {
                foreach ($this->processes as $process) {
                    if ($process->isStarted()) {
                        continue;
                    }
                    $process->start();
                    if ($this->aliveCount() >= $this->max) {
                        break;
                    }
                }
            }
            if ($block) {
                usleep($sleep);
            }
        } while ($block);
    }

    /**
     * get the count of stopped processes
     *
     * @return int
     */
    public function stoppedCount(): int
    {
        $count = 0;
        foreach ($this->processes as $process) {
            if ($process->isStopped()) {
                $count++;
            }
        }

        return $count;
    }
}
