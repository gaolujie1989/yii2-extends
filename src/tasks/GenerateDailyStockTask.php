<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\fulfillment\DailyStockGenerator;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class GenerateDailyStockTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GenerateDailyStockTask extends CronTask implements ProgressInterface
{
    use ProgressTrait;

    /**
     * @var string
     */
    public $stockDateFrom = '-2 days';

    /**
     * @var string
     */
    public $stockDateTo = '-1 days';

    /**
     * @var int
     */
    public $timeStep = 86400;

    /**
     * @var DailyStockGenerator
     */
    public $dailyStockGenerator = DailyStockGenerator::class;

    /**
     * @return \Generator
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->dailyStockGenerator = Instance::ensure($this->dailyStockGenerator, DailyStockGenerator::class);
        $dateAtFrom = is_numeric($this->stockDateFrom) ? $this->stockDateFrom : strtotime($this->stockDateFrom);
        $dateAtTo = is_numeric($this->stockDateTo) ? $this->stockDateTo : strtotime($this->stockDateTo);

        $total = ceil(($dateAtTo - $dateAtFrom) / 86400);
        $progress = $this->getProgress($total);
        for ($stockDateAt = $dateAtFrom; $stockDateAt <= $dateAtTo; $stockDateAt += $this->timeStep) {
            $date = date('Y-m-d', $stockDateAt);
            $progress->message = "[{$date}][GenerateDailyStockMovements]";
            yield true;
            if ($this->dailyStockGenerator->generateDailyStockMovements($stockDateAt, $stockDateAt + $this->timeStep)) {
                $progress->message = "[{$date}][GenerateDailyStocks]";
                yield true;
                $this->dailyStockGenerator->generateDailyStocks($stockDateAt, $stockDateAt + $this->timeStep);
            }
            $progress->done++;
        }
        return true;
    }
}
