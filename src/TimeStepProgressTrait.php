<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\executing;

use lujie\extend\helpers\ValueHelper;

/**
 * Trait TimeStepTrait
 *
 * @property int|string $timeFrom = '-1 days'
 * @property int|string $timeTo = 'now'
 * @property string $timeFromFormat = null //'Y-m-d 00:00:00'
 * @property string $timeToFormat = null //'Y-m-d 23:59:59'
 * @property int $timeStep = 86400
 * @property string $format = 'Y-m-d H:i:s'
 * @property string $messageTemplate = [{timeFrom}->{timeTo}]
 *
 * @package lujie\executing
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
trait TimeStepProgressTrait
{
    use ProgressTrait;

    /**
     * @return \Generator
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        return $this->executeProgress();
    }

    /**
     * @param array $params
     * @param int $totalCount
     * @return \Generator
     * @inheritdoc
     */
    protected function executeProgress(array $params = [], int $totalCount = 1): \Generator
    {
        $timeStep = $this->timeStep ?? 86400;
        $format = $this->format ?? 'Y-m-d H:i:s';
        $messageTemplate = $this->messageTemplate ?? '[{timeFrom}->{timeTo}]';

        $timeAtFrom = ValueHelper::formatDateTime($this->timeFrom ?? '-1 days', $this->timeFromFormat ?? null);
        $timeAtTo = ValueHelper::formatDateTime($this->timeTo ?? 'now', $this->timeToFormat ?? null);

        $total = ceil(($timeAtTo - $timeAtFrom) / $timeStep);
        $progress = $this->getProgress($total * $totalCount);

        for ($timeAt = $timeAtFrom; $timeAt <= $timeAtTo; $timeAt += $timeStep) {
            $stepTimeToAt = min($timeAt + $timeStep - 1, $timeAtTo);
            $progress->message = strtr($messageTemplate, [
                '{timeFrom}' => date($format, $timeAt),
                '{timeTo}' => date($format, $stepTimeToAt),
            ]);
            yield true;
            $this->executeTimeStep($timeAt, $stepTimeToAt, $params);
            $progress->done++;
        }
        yield true;
    }

    /**
     * @param int $timeAtFrom
     * @param int $timeAtTo
     * @param array $params
     * @inheritdoc
     */
    abstract protected function executeTimeStep(int $timeAtFrom, int $timeAtTo, array $params = []): void;
}