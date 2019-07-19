<?php
/**
 * @copyright Copyright (c) 2018
 */

namespace lujie\extend\helpers;


use Jenner\SimpleFork\FixedPool;
use Jenner\SimpleFork\Process;
use Yii;

/**
 * Class SimpleForkHelper
 * @package lujie\extend\helpers
 * @author Lujie Zhou <gao_lujie@live.cn>
 * @deprecated
 */
class SimpleForkHelper
{
    /**
     * @inheritdoc
     */
    public static function resetConnections(): void
    {
        foreach (Yii::$app->getComponents(false) as $component) {
            if (method_exists($component, 'close') && method_exists($component, 'open')) {
                $component->close();
                $component->open();
            }
        }
    }

    /**
     * @param $start
     * @param $end
     * @param $callable
     * @param null $multi
     * @param int $step
     * @param int $seconds
     * @param bool $block
     * @return FixedPool
     * @inheritdoc
     */
    public static function parallelFor($start, $end, $callable, $multi = null, $step = 1, $seconds = 5, $block = true)
    {
        $interval = $seconds * 1000000;
        $max = $multi ?: intval(($end - $start) / $step);
        $pool = new FixedPool($max);
        for ($i = $start; $i <= $end; $i = $i + $step) {
            while ($block && $pool->aliveCount() >= $max) {
                usleep($interval);
            }
            $pool->execute(new Process(function () use ($callable, $i) {
                SimpleForkHelper::resetConnections();
                call_user_func($callable, $i);
            }));
        }
        $pool->wait($block, $interval);
        return $pool;
    }

    /**
     * @param $array
     * @param $callable
     * @param null $multi
     * @param int $seconds
     * @param bool $block
     * @return FixedPool
     * @inheritdoc
     */
    public static function parallelForEach($array, $callable, $multi = null, $seconds = 5, $block = true)
    {
        $interval = $seconds * 1000000;
        $max = $multi ?: count($array);
        $pool = new FixedPool($max);
        foreach ($array as $item) {
            while ($block && $pool->aliveCount() >= $max) {
                usleep($interval);
            }
            $pool->execute(new Process(function () use ($callable, $item) {
                SimpleForkHelper::resetConnections();
                call_user_func($callable, $item);
            }));
        }
        $pool->wait($block, $interval);
        return $pool;
    }

    /**
     * @return \Generator
     * @inheritdoc
     */
    protected static function getForeverGenerator()
    {
        while (true) {
            yield 1;
        }
    }

    /**
     * @param $callable
     * @param int $multi
     * @param int $seconds
     * @inheritdoc
     */
    public static function parallelForever($callable, $multi = 1, $seconds = 5)
    {
        $interval = $seconds * 1000000;
        static::parallelForEach(static::getForeverGenerator(), $callable, $multi, $interval);
    }

    /**
     * @param FixedPool[] $pools
     * @param int $seconds
     * @param bool $block
     * @inheritdoc
     */
    public static function waitPools($pools, $seconds = 5, $block = true)
    {
        $interval = $seconds * 1000000;
        foreach ($pools as $pool) {
            $pool->wait($block, $interval);
        }
    }


    public static function parallelMultiForever($callableList, $multi = 1, $seconds = 5)
    {
        $interval = $seconds * 1000000;
        $max = count($callableList) * $multi;
        $pool = new FixedPool($max);

        foreach ($callableList as $callable) {
            for ($i = 0; $i < $multi; $i++) {
                $pool->execute(new Process(function () use ($callable, $i) {
                    SimpleForkHelper::resetConnections();
                    call_user_func($callable, $i);
                }));
            }
        }

        $processes = $pool->getProcesses();
        do {
            foreach ($processes as $process) {
                if ($process->isRunning()) continue;
                $process->start();
            }
            usleep($interval);
        } while (true);
        return $pool;
    }
}
