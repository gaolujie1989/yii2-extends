<?php
/**
 * @copyright Copyright (c) 2019
 */

namespace lujie\fulfillment\tasks;

use lujie\executing\ProgressInterface;
use lujie\executing\ProgressTrait;
use lujie\executing\TimeStepProgressTrait;
use lujie\fulfillment\FulfillmentDailyStockGenerator;
use lujie\scheduling\CronTask;
use yii\base\InvalidConfigException;
use yii\di\Instance;

/**
 * Class GenerateDailyStockTask
 * @package lujie\fulfillment\tasks
 * @author Lujie Zhou <gao_lujie@live.cn>
 */
class GenerateFulfillmentDailyStockTask extends CronTask implements ProgressInterface
{
    use TimeStepProgressTrait;

    /**
     * @var string
     */
    public $timeFrom = '-2 days';

    /**
     * @var string
     */
    public $timeTo = '-1 days';

    /**
     * @var int
     */
    public $timeStep = 86400;

    /**
     * @var FulfillmentDailyStockGenerator
     */
    public $dailyStockGenerator = FulfillmentDailyStockGenerator::class;

    /**
     * @return \Generator
     * @throws InvalidConfigException
     * @inheritdoc
     */
    public function execute(): \Generator
    {
        $this->dailyStockGenerator = Instance::ensure($this->dailyStockGenerator, FulfillmentDailyStockGenerator::class);
        return $this->executeProgress();
    }

    /**
     * @param int $timeAtFrom
     * @param int $timeAtTo
     * @param array $params
     * @throws InvalidConfigException
     * @inheritdoc
     */
    protected function executeTimeStep(int $timeAtFrom, int $timeAtTo, array $params = []): void
    {
        if ($this->dailyStockGenerator->generateDailyStockMovements($timeAtFrom, $timeAtTo)) {
            $this->dailyStockGenerator->generateDailyStocks($timeAtFrom, $timeAtTo);
        }
    }
}
